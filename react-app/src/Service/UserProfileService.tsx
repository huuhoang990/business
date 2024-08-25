import axios from "axios"
import { handleError } from "@/Helpers/ErrorHandler"
import { UserProfileResponse, UserProfile } from "@/Types/User"

const api = process.env.API_URL + "/api";

export const getUserProfileApi = async () :Promise<UserProfile | null> => {
  try {
    const token = localStorage.getItem("token")
    console.log(token)

    axios.defaults.headers.common["Authorization"] = "Bearer " + token
    const response = await axios.get<UserProfileResponse>(api + "/auth/profile")

    return response.data.data
  } catch (error) {
    handleError(error)
    return null
  }
}
