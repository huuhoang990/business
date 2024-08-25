import axios from "axios"
import { handleError } from "@/Helpers/ErrorHandler"
import { AccessToken } from "@/Types/Auth"
import { ProvinceResponse, Province } from "@/Types/Province"
import { DistrictResponse, District } from "@/Types/District"
import { RegisterForm } from "@/Types/Forms/RegisterForm"

const api = process.env.API_URL + "/api";

export const registerUserApi = async (form: RegisterForm) => {
  try {
    const data = await axios.post<AccessToken>(api + "/register", form)
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

export const getDistrictByProvinceIdApi = async (provinceId: string): Promise<District[]> => {
  try {
    const response = await axios.get<DistrictResponse>(api + "/district/" + provinceId)
    return response.data.data
  } catch (error) {
    handleError(error)
    return []
  }
}

export const getWardByDistrictIdApi = async (getWardByDCode: string): Promise<District[]> => {
  try {
    const response = await axios.get<DistrictResponse>(api + "/ward/" + getWardByDCode)
    return response.data.data
  } catch (error) {
    handleError(error)
    return []
  }
}
