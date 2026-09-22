package password

import (
	"golang.org/x/crypto/bcrypt"
)

// Hash 使用 bcrypt 生成密码哈希（与 Laravel Hash::make / bcrypt 100% 算法兼容）
func Hash(plainPassword string) (string, error) {
	bytes, err := bcrypt.GenerateFromPassword([]byte(plainPassword), bcrypt.DefaultCost)
	return string(bytes), err
}

// Verify 验证密码是否与哈希匹配
func Verify(hash, plainPassword string) bool {
	err := bcrypt.CompareHashAndPassword([]byte(hash), []byte(plainPassword))
	return err == nil
}
