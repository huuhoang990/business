import { useEffect, useState } from "react"
import { getUserProfileApi } from "@/Service/UserProfileService"
import { useDispatch } from 'react-redux'
import { showLoading, hideLoading } from '@/Store/loadingSlice'
import { UserProfile } from "@/Types/User"

const Home = () => {
  const dispatch = useDispatch();
  const [userProfile, setUserProfile] = useState<UserProfile>()

  const fetchUser = async () => {
    dispatch(showLoading());
    try {
      const data = await getUserProfileApi()
      if (data != null) {
        setUserProfile(data)
      }
    } finally {
      dispatch(hideLoading());
    }
  }

  useEffect(() => {
    fetchUser()
  }, [])

  return (
    <div id="inner-content" className="my-5 w-100 justify-content-center align-self-center">
      <div className="row">
        <div className="col">
          <div className="card">
            <div className="card-body p-4 p-md-5">
              <h3 className="mb-4 pb-2 pb-md-0 mb-md-5">Home</h3>
              {
                userProfile ?
                  <table className="table">
                    <tr>
                      <th>Email</th>
                      <td>{userProfile.email}</td>
                    </tr>
                    <tr>
                      <th>First name</th>
                      <td>{userProfile.firstName}</td>
                    </tr>
                    <tr>
                      <th>Last name</th>
                      <td>{userProfile.lastName}</td>
                    </tr>
                    <tr>
                      <th>Birthday</th>
                      <td>{userProfile.birthday}</td>
                    </tr>
                    <tr>
                      <th>City</th>
                      <td>{userProfile.city}</td>
                    </tr>
                    <tr>
                      <th>District</th>
                      <td>{userProfile.district}</td>
                    </tr>
                    <tr>
                      <th>Ward</th>
                      <td>{userProfile.ward}</td>
                    </tr>
                  </table>
                : ""
              }

            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Home