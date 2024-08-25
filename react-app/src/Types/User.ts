export type User = {
  userName: string
  email: string
}

export type UserProfile = {
  email: string
  firstName: string
  lastName: string
  birthday: string
  city: string
  district: string
  ward: string
  address: string
}

export type UserProfileResponse = {
  data: UserProfile
}
