import axios from "axios"
import { handleError } from "@/Helpers/ErrorHandler"
import { UserProfileToken } from "@/Models/User"

const api = "http://localhost:8000/api/"

export const loginApi = async (email: string, password: string) => {
  try {
    const data = await axios.post<UserProfileToken>(api + "auth/login", {
      email: email,
      password: password
    })

    return data
  } catch (error) {
    handleError(error)
  }
}

export const registerApi = async (email: string, userName: string, password: string) => {
  try {
    const data = await axios.post<UserProfileToken>(api + "account/register", {
      email: email,
      userName: userName,
      password: password
    })

    return data
  } catch (error) {
    handleError(error)
  }
}
