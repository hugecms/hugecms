import { useState, useEffect } from 'react'

export interface SeckillCountdown {
  hours: string
  minutes: string
  seconds: string
}

export function useSeckillTimer(initialSeconds = 7200) {
  const [timeLeft, setTimeLeft] = useState(initialSeconds)

  useEffect(() => {
    const timer = setInterval(() => {
      setTimeLeft((prev) => (prev > 0 ? prev - 1 : initialSeconds))
    }, 1000)
    return () => clearInterval(timer)
  }, [initialSeconds])

  const hours = String(Math.floor(timeLeft / 3600)).padStart(2, '0')
  const minutes = String(Math.floor((timeLeft % 3600) / 60)).padStart(2, '0')
  const seconds = String(timeLeft % 60).padStart(2, '0')

  return {
    timeLeft,
    hours,
    minutes,
    seconds,
  }
}

export default useSeckillTimer
