package jwt

import (
	"context"
	"errors"
	"time"

	"github.com/gogf/gf/v2/frame/g"
	"github.com/golang-jwt/jwt/v5"
)

var (
	ErrTokenExpired = errors.New("token has expired")
	ErrTokenInvalid = errors.New("token is invalid")
)

// CustomClaims 自定义 JWT 载荷
type CustomClaims struct {
	UserId   int64  `json:"uid"`
	Email    string `json:"email"`
	Name     string `json:"name"`
	IsSuper  bool   `json:"is_super"`
	jwt.RegisteredClaims
}

// getSecret 获取 JWT 密钥
func getSecret(ctx context.Context) []byte {
	secret := g.Cfg().MustGet(ctx, "jwt.secret", "hugecms-jwt-default-secret-key-32bytes!").String()
	return []byte(secret)
}

// getExpireDuration 获取 JWT 过期时间
func getExpireDuration(ctx context.Context) time.Duration {
	expireStr := g.Cfg().MustGet(ctx, "jwt.expire", "7d").String()
	duration, err := time.ParseDuration(expireStr)
	if err != nil {
		return 7 * 24 * time.Hour
	}
	return duration
}

// GenerateToken 签发 JWT Token
func GenerateToken(ctx context.Context, userId int64, email, name string, isSuper bool) (string, time.Time, error) {
	expireDuration := getExpireDuration(ctx)
	expireTime := time.Now().Add(expireDuration)

	claims := CustomClaims{
		UserId:  userId,
		Email:   email,
		Name:    name,
		IsSuper: isSuper,
		RegisteredClaims: jwt.RegisteredClaims{
			ExpiresAt: jwt.NewNumericDate(expireTime),
			IssuedAt:  jwt.NewNumericDate(time.Now()),
			NotBefore: jwt.NewNumericDate(time.Now()),
			Issuer:    "hugecms",
		},
	}

	token := jwt.NewWithClaims(jwt.SigningMethodHS256, claims)
	tokenString, err := token.SignedString(getSecret(ctx))
	return tokenString, expireTime, err
}

// ParseToken 解析 JWT Token
func ParseToken(ctx context.Context, tokenString string) (*CustomClaims, error) {
	token, err := jwt.ParseWithClaims(tokenString, &CustomClaims{}, func(token *jwt.Token) (interface{}, error) {
		return getSecret(ctx), nil
	})

	if err != nil {
		if errors.Is(err, jwt.ErrTokenExpired) {
			return nil, ErrTokenExpired
		}
		return nil, ErrTokenInvalid
	}

	if claims, ok := token.Claims.(*CustomClaims); ok && token.Valid {
		return claims, nil
	}

	return nil, ErrTokenInvalid
}
