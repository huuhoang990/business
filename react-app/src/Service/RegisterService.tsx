import axios from "axios"
import { handleError } from "@/Helpers/ErrorHandler"
import { AccessToken } from "@/Types/Auth"
import { ProvinceResponse, Province } from "@/Types/Province"
import { RegisterForm } from "@/Types/Forms/RegisterForm"

// const api = process.env.API_URL + "/api";
const api = "http://aaaa.com/api";

export const registerUserApi = async (form: RegisterForm) => {
  try {
    const data = await axios.post<AccessToken>(api + "/account/register", {
      form
    })

    return data
  } catch (error) {
    handleError(error)
  }
}

export const getProvincesApi = async (): Promise<Province[]> => {
  try {
    const response = await axios.get<ProvinceResponse>(api + "/provinces")
    return response.data.data
  } catch (error) {
    handleError(error)
    return []
  }
}
