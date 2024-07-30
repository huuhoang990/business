import { RouterProvider } from 'react-router-dom'
import { router } from "@/Routes/Routes"
import "react-toastify/ReactToastify.min.css"
import '@/assets/css/style.scss'
import 'bootstrap/dist/css/bootstrap.min.css'
import { ToastContainer } from "react-toastify"
import { UserProvider } from './Context/userAuth'

function App() {
  return (
      <>
        <UserProvider>
          <RouterProvider router={router} />
          <ToastContainer />
        </UserProvider>
      </>
  )
}

export default App
