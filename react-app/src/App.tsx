import "react-toastify/ReactToastify.min.css"
import "@/assets/css/style.scss"
import "bootstrap/dist/css/bootstrap.min.css"
import { ToastContainer } from "react-toastify"
import { UserProvider } from "./Context/userAuth"
import { Outlet } from "react-router"

function App() {
  return (
      <>
        <UserProvider>
          <Outlet />
          <ToastContainer />
        </UserProvider>
      </>
  )
}

export default App
