import { RouterProvider } from 'react-router-dom'
import { router } from "@/Routes/Routes"
import "react-toastify/ReactToastify.min.css"
import '@/assets/css/style.scss'
import 'bootstrap/dist/css/bootstrap.min.css'
import { ToastContainer } from "react-toastify"

function App() {
  return (
      <>
        <RouterProvider router={router} />
        <ToastContainer />
      </>
  )
}

export default App
