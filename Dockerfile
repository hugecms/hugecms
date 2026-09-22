# Multi-stage Dockerfile for HugeCMS
# Stage 1: Build binary statically
FROM golang:1.24-alpine AS builder

WORKDIR /src

# Install git and build tools
RUN apk add --no-cache git ca-certificates tzdata

# Cache go modules
COPY go.mod go.sum ./
RUN go env -w GOPROXY=https://goproxy.cn,direct && go mod download

# Copy source code
COPY . .

# Statically compile the binary for linux/amd64
RUN CGO_ENABLED=0 GOOS=linux GOARCH=amd64 go build \
    -ldflags="-w -s -extldflags '-static'" \
    -o /app/hugecms main.go

# Stage 2: Minimal runtime image
FROM alpine:3.21

# Maintainer info
LABEL maintainer="HugeCMS Team"

# Set timezone and install CA certificates
RUN apk add --no-cache ca-certificates tzdata \
    && cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo "Asia/Shanghai" > /etc/timezone

WORKDIR /app

# Copy binary from builder
COPY --from=builder /app/hugecms /app/hugecms

# Copy runtime assets and config
COPY resource /app/resource
COPY manifest /app/manifest

# Create upload directory with permissions
RUN mkdir -p /app/resource/public/upload && chmod -R 777 /app/resource/public/upload

# Expose HTTP port
EXPOSE 8000

# Healthcheck
HEALTHCHECK --interval=15s --timeout=3s --retries=3 \
  CMD wget --spider -q http://127.0.0.1:8000/robots.txt || exit 1

# Start server
ENTRYPOINT ["/app/hugecms"]
