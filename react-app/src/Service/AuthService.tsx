import axios from "axios"
import { handleError } from "@/Helpers/ErrorHandler"
import { AccessToken } from "@/Types/Auth"

const api = process.env.API_URL + "/api";

export const loginApi = async (email: string, password: string) => {
  try {
    const data = await axios.post<AccessToken>(api + "/auth/login", {
      email: email,
      password: password
    })

    return data
  } catch (error) {
    handleError(error)
  }
}
