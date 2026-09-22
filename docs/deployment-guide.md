# HugeCMS 生产环境部署与容器化运行指南

HugeCMS 基于 GoFrame 2.x 框架构建，支持**容器化一键部署**与**原生二进制独立部署**两种方式。

---

## 方案一：Docker & Docker Compose 容器化部署（强烈推荐）

### 1. 前置依赖准备
- 目标服务器需已安装 `Docker (>= 20.10)` 和 `Docker Compose (>= 2.0)`。

### 2. 获取代码与构建运行
在项目根目录下执行：

```bash
# 1. 启动容器编排集群 (包含应用与 MySQL 8.0)
docker compose up -d

# 2. 查看容器运行状态
docker compose ps

# 3. 查看应用服务实时日志
docker compose logs -f app
```

> **自动初始化说明**：
> 首次启动时，MySQL 容器会自动加载 `docs/db.sql` 完成数据表创建和预置数据灌入。
> 当 MySQL 健康检查通过后，`hugecms-app` 容器将自动启动并监听 `8000` 端口。

### 3. 数据持久化路径
- **上传附件**：持久化保存在宿主机的 `./data/upload` 目录。
- **MySQL 数据**：持久化保存在宿主机的 `./data/mysql` 目录。

### 4. 服务启停与维护
```bash
# 停止运行
docker compose down

# 重新拉取构建并启动
docker compose up -d --build

# 备份数据库
docker exec -t hugecms-mysql mysqldump -u root -proot123456 hugecms > ./data/backup_$(date +%F).sql
```

---

## 方案二：Linux 服务器原生二进制部署

### 1. 本地交叉编译 Linux 二进制程序
在开发机器（Windows 或 macOS）的根目录下运行：

```bash
# Windows PowerShell
$env:CGO_ENABLED="0"
$env:GOOS="linux"
$env:GOARCH="amd64"
go build -ldflags="-w -s" -o output/hugecms main.go

# Linux / macOS Bash
CGO_ENABLED=0 GOOS=linux GOARCH=amd64 go build -ldflags="-w -s" -o output/hugecms main.go
```

### 2. 打包传输到服务器
将以下目录和文件打包上传至服务器（例如 `/opt/hugecms`）：
```text
/opt/hugecms/
├── hugecms                 # 编译生成的二进制文件 (chmod +x hugecms)
├── manifest/
│   └── config/
│       └── config.yaml     # 线上生产环境配置
└── resource/
    ├── admin/dist/         # 管理后台已构建的静态产物
    ├── template/           # 前台多主题模板目录
    └── public/             # 公共静态资源与 upload 目录
```

### 3. 配置 Systemd 常驻守护进程
创建 `/etc/systemd/system/hugecms.service` 文件：

```ini
[Unit]
Description=HugeCMS Web Application Service
After=network.target mysql.service

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/opt/hugecms
ExecStart=/opt/hugecms/hugecms
Restart=always
RestartSec=5
LimitNOFILE=65535

[Install]
WantedBy=multi-user.target
```

启用并启动服务：
```bash
systemctl daemon-reload
systemctl enable hugecms
systemctl start hugecms
systemctl status hugecms
```

---

## 方案三：生产环境 Nginx 反向代理与 SSL 配置

生产环境通常推荐使用 Nginx 作为反向代理层，处理 HTTPS、HTTP/2 协议加速与大文件静态缓存。

### 推荐 Nginx 配置示例 (`/etc/nginx/conf.d/hugecms.conf`)

```nginx
server {
    listen 80;
    server_name your-domain.com;
    # 强制重定向至 HTTPS
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com;

    # SSL 证书配置
    ssl_certificate     /etc/nginx/ssl/your-domain.com.crt;
    ssl_certificate_key /etc/nginx/ssl/your-domain.com.key;
    ssl_protocols       TLSv1.2 TLSv1.3;
    ssl_ciphers         HIGH:!aNULL:!MD5;

    # 上传文件大小限制
    client_max_body_size 100m;

    # 静态附件资源由 Nginx 直接高效处理 (可选，建议开启)
    location /upload/ {
        alias /opt/hugecms/resource/public/upload/;
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }

    # 主程序反向代理
    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

---

## 验证与健康检查

部署完成后，可通过以下接口验证服务是否运行正常：
- **前台首页**：`curl -i http://localhost:8000/`
- **Sitemap**：`curl -i http://localhost:8000/sitemap.xml`
- **Robots**：`curl -i http://localhost:8000/robots.txt`
- **管理后台**：浏览器打开 `http://localhost:8000/admin`
