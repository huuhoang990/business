export type District = {
  id: string,
  name: string,
  provinceId: string
}

export type DistrictResponse = {
  data: District[]
}
