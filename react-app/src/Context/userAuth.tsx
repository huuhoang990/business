import { UserProfile } from "@/Models/User"
import React, { createContext } from "react"
import { useNavigate } from "react-router-dom"

type UserContextType = {
  userName: string
  token: string | null
  registerUser: (email: string, userName: string, password: string) => void
  loginUser: (userName: string, password: string) => void
  logout: () => void
  isLoggedIn: () => boolean
}

type Props = { children: React.ReactNode }

const UserContext = createContext<UserContextType>({} as UserContextType)

export const UserProvider = ({ children }: Props) => {
  const navigate = useNavigate()
}

