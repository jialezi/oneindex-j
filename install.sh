#!/bin/bash

# ====================================================
#	System Request:Debian 8、9
#	OneIndex一键安装脚本
# ====================================================

#fonts color
Green="\033[32m" 
Red="\033[31m" 
Blue="\033[33m"
Font="\033[0m"

#notification information
Info="${Green}[Info]${Font}"
OK="${Green}[OK]${Font}"
Error="${Red}[Error]${Font}"

#folder
nginx_conf_dir="/etc/nginx/conf.d"

source /etc/os-release &>/dev/null
# 系统检测、仅支持 Debian8+
check_system(){
	KernelBit="$(getconf LONG_BIT)"
    if [[ "${ID}" == "debian" && ${VERSION_ID} -ge 8 ]];then
        echo -e "${OK} ${Blue} 当前系统为 Debian ${VERSION_ID} ${Font} "
    else
        echo -e "${Error} ${Red} 当前系统为不在支持的系统列表内，安装中断 ${Font} "
        exit 1
    fi
}
# 判定是否为root用户
is_root(){
    if [ `id -u` == 0 ]
        then echo -e "${OK} ${Blue} 当前用户是root用户，进入安装流程 ${Font} "
        sleep 1
    else
        echo -e "${Error} ${Red} 当前用户不是root用户，请切换到root用户后重新执行脚本 ${Font}" 
        exit 1
    fi
}
debian_source(){
    # 添加源
    echo "deb http://packages.dotdeb.org jessie all" | tee --append /etc/apt/sources.list
    echo "deb-src http://packages.dotdeb.org jessie all" | tee --append /etc/apt/sources.list
    # 添加key
    wget --no-check-certificate https://www.dotdeb.org/dotdeb.gpg
    if [[ -f dotdeb.gpg ]];then
        apt-key add dotdeb.gpg
        if [[ $? -eq 0 ]];then
            echo -e "${OK} ${Blue} 导入 GPG 秘钥成功 ${Font}"
            sleep 1
        else
            echo -e "${Error} ${Red} 导入 GPG 秘钥失败 ${Font}"
            exit 1
        fi
    else
        echo -e "${Error} ${Red} 下载 GPG 秘钥失败 ${Font}"
        exit 1
    fi
}

basic_dependency(){
    apt update
    apt install wget unzip net-tools bc curl sudo -y     
}

nginx_install(){
        if [ ${VERSION_ID} -eq 8 ];then
        debian_source
        fi
        apt update -y
        apt install nginx -y
        if [[ $? -eq 0 ]];then
            echo -e "${OK} ${Blue} nginx 安装成功 ${Font}"
            sleep 1
        else
            echo -e "${Error} ${Red} nginx 安装失败 ${Font}"
            exit 1
        fi   
}

php7_install(){
        apt install php7.0-cgi php7.0-fpm php7.0-curl php7.0-gd -y
        if [[ $? -eq 0 ]];then
            echo -e "${OK} ${Blue} php7 安装成功 ${Font}"
            sleep 1
        else
            echo -e "${Error} ${Red} php7 安装失败 ${Font}"
            exit 1
        fi  
}

nginx_conf_ssl_add(){
	cat > ${nginx_conf_dir}/OneIndex.conf <<EOF
server
    {
        listen 443 ssl http2;
        add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
        server_name ${domain};
        root /home/wwwroot/${domain};
        index index.html index.php;
        ssl on;
        ssl_certificate /home/wwwroot/ssl/OneIndex.crt;
        ssl_certificate_key /home/wwwroot/ssl/OneIndex.key;
        ssl_session_timeout 5m;
        ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
        ssl_prefer_server_ciphers on;
        ssl_ciphers "EECDH+CHACHA20:EECDH+CHACHA20-draft:EECDH+AES128:RSA+AES128:EECDH+AES256:RSA+AES256:EECDH+3DES:RSA+3DES:!MD5";
        ssl_session_cache builtin:1000 shared:SSL:10m;
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/run/php/php7.0-fpm.sock;
        }
        location / {
             try_files \$uri \$uri/ /index.php?\$args;
        }
        location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
        {
            expires      30d;
        }
    
        location ~ .*\.(js|css)?$
        {
            expires      12h;
        }
        access_log off;
    }
server
    {
        listen 80;
        server_name ${domain};
        rewrite ^(.*) https://${domain}\$1 permanent;
    }
EOF
    if [[ $? -eq 0 ]];then
        echo -e "${OK} ${Blue} nginx 配置导入成功 ${Font}"
        sleep 1
    else
        echo -e "${Error} ${Red} nginx 配置导入失败 ${Font}"
        exit 1
    fi
}
ssl_install(){
    apt install socat netcat -y
    if [[ $? -eq 0 ]];then
        echo -e "${OK} ${Blue} SSL 证书生成脚本依赖安装成功 ${Font}"
        sleep 2
    else
        echo -e "${Error} ${Red} SSL 证书生成脚本依赖安装失败 ${Font}"
        exit 6
    fi

    curl  https://get.acme.sh | sh

    if [[ $? -eq 0 ]];then
        echo -e "${OK} ${Blue} SSL 证书生成脚本安装成功 ${Font}"
        sleep 2
    else
        echo -e "${Error} ${Red} SSL 证书生成脚本安装失败，请检查相关依赖是否正常安装 ${Font}"
        exit 7
    fi

}
acme(){
    mkdir -p /home/wwwroot/ssl
    ~/.acme.sh/acme.sh --issue -d ${domain} --standalone -k ec-256 --force
    if [[ $? -eq 0 ]];then
        echo -e "${OK} ${Blue} SSL 证书生成成功 ${Font}"
        sleep 2
        ~/.acme.sh/acme.sh --installcert -d ${domain} --fullchainpath /home/wwwroot/ssl/OneIndex.crt --keypath /home/wwwroot/ssl/OneIndex.key --ecc
        if [[ $? -eq 0 ]];then
        echo -e "${OK} ${Blue} 证书配置成功 ${Font}"
        sleep 2
        else
        echo -e "${Error} ${Red} 证书配置失败 ${Font}"
        fi
    else
        echo -e "${Error} ${Red} SSL 证书生成失败 ${Font}"
        exit 1
    fi
}

port_exist_check(){
    if [[ 0 -eq `netstat -tlpn | grep "$1"| wc -l` ]];then
        echo -e "${OK} ${Green} $1 端口未被占用 ${Font}"
        sleep 1
    else
        echo -e "${Error} ${Red} $1 端口被占用，请检查占用进程 结束后重新运行脚本 ${Font}"
        netstat -tlpn | grep "$1"
        exit 1
    fi
}

OneIndex_install(){
    apt install git cron -y
    apt install git -y
    mkdir -p /home/wwwroot/${domain} && cd /home/wwwroot/${domain}
	git clone https://github.com/july929/oneindex-j.git && mv oneindex-j/* ./
    chmod -R 777 config/ cache/
    if [[ $? -eq 0 ]];then
        echo -e "${OK} ${Blue} OneIndex 下载成功 ${Font}"
        sleep 1
    else
        echo -e "${Error} ${Red} OneIndex 下载失败 ${Font}"
        exit 1
    fi
echo "0 * * * * /usr/bin/php /home/wwwroot/${domain}/one.php token:refresh
*/15 * * * * /usr/bin/php /home/wwwroot/${domain}/one.php cache:refresh" > bt.cron
crontab bt.cron
rm -rf bt.cron
}

domain_check(){
    stty erase '^H' && read -p "请输入你的OneIndex域名信息(如：OneIndex.yangwenqing.com):" domain
    domain_ip=`ping ${domain} -c 1 | sed '1{s/[^(]*(//;s/).*//;q}'`
    local_ip=`curl http://whatismyip.akamai.com`
    echo -e "域名dns解析IP：${domain_ip}"
    echo -e "本机IP: ${local_ip}"
    sleep 2
    if [[ $(echo ${local_ip}|tr '.' '+'|bc) -eq $(echo ${domain_ip}|tr '.' '+'|bc) ]];then
        echo -e "${OK} ${Blue} 域名dns解析IP  与 本机IP 匹配 ${Font}"
        sleep 2
    else
        echo -e "${Error} ${Red} 域名dns解析IP 与 本机IP 不匹配 是否继续安装？（y/n）${Font}" && read install
        case $install in
        [yY][eE][sS]|[yY])
            echo -e "${Blue} 继续安装 ${Font}" 
            sleep 2
            ;;
        *)
            echo -e "${Red} 安装终止 ${Font}" 
            exit 2
            ;;
        esac
    fi
}
standard(){
    basic_dependency
    domain_check
    nginx_install
    php7_install
    OneIndex_install
}
ssl(){

    service nginx stop
    service php7.0-fpm stop

    port_exist_check 80
    port_exist_check 443

    ssl_install
    acme
    nginx_conf_ssl_add

    service nginx start
    service php7.0-fpm start
}

main(){
    check_system
    is_root
	sleep 2
            standard
            ssl
}

main


