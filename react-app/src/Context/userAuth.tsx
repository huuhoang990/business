import { User } from "@/Types/User"
import { loginApi/*, registerApi*/ } from "@/Service/AuthService"
import axios from "axios"
import React, { createContext, useEffect, useState } from "react"
import { useNavigate } from "react-router-dom"
import { toast } from "react-toastify"

type UserContextType = {
  user: User | null
  token: string | null
  // registerUser: (email: string, userName: string, password: string) => void
  loginUser: (userName: string, password: string) => void
  logout: () => void
  isLoggedIn: () => boolean
}

type Props = { children: React.ReactNode }

const UserContext = createContext<UserContextType>({} as UserContextType)

export const UserProvider = ({ children }: Props) => {
  const navigate = useNavigate()
  const [token, setToken] = useState<string | null>(null)
  const [user, setUser] = useState<User | null>(null)
  const [isReady, setIsReady] = useState(false)

  useEffect(() => {
    const user = localStorage.getItem("user")
    const token = localStorage.getItem("token")
    console.log(token)
    if (user && token) {
      setUser(JSON.parse(user))
      setToken(token)
      axios.defaults.headers.common["Authorization"] = "Bearer " + token
    }

    setIsReady(true)
  }, [])
/*
  const registerUser = async (
    email: string,
    userName: string,
    password: string
  ) => {
    await registerApi(email, userName, password).then((res: any) => {
      if (res) {
        localStorage.setItem("token", res?.data.token)
        const userObj = {
          userName: res?.data.userName,
          email: res?.data.email
        }
        localStorage.setItem("user", JSON.stringify(userObj))
        setToken(res?.data.token!)
        setUser(userObj!)
        toast.success("Login Success!")
        navigate("/search")
      }
    }).catch(e => toast.warning("Server error occured"))
  }
*/
  const loginUser = async (
    userName: string,
    password: string
  ) => {
    await loginApi(userName, password).then((res: any) => {
      if (res) {
        console.log("res 111")
        console.log(res)
        localStorage.setItem("token", res?.data.access_token)
        const userObj = {
          userName: res?.data.userName,
          email: res?.data.email
        }
        localStorage.setItem("user", JSON.stringify(userObj))
        setToken(res?.data.access_token!)
        setUser(userObj!)
        toast.success("Login Success!")
        navigate("/")
      }
    }).catch(() => toast.warning("Server error occured"))
  }

  const isLoggedIn = () => {
    return !!user
  }

  const logout = () => {
    localStorage.removeItem("token")
    localStorage.removeItem("user")
    setUser(null)
    setToken("")
    navigate("/")
  }
  // loginUser, user, token, logout, isLoggedIn , registerUser
  return (
    <UserContext.Provider value={{ loginUser, user, token, logout, isLoggedIn }} >
      { isReady ? children : null }
    </UserContext.Provider>
  )
}

export const useAuth = () => React.useContext(UserContext)
