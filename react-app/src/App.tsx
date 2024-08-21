import "react-toastify/ReactToastify.min.css"
import "@/assets/css/style.scss"
import "bootstrap/dist/css/bootstrap.min.css"
import { ToastContainer } from "react-toastify"
import { UserProvider } from "./Context/userAuth"
import { Outlet } from "react-router"
import { store } from '@/Store'
import { Provider } from 'react-redux'
import LoadingSpinner from '@/Components/LoadingSpinner'

function App() {
  return (
      <>
        <Provider store={store}>
          <UserProvider>
            <Outlet />
            <ToastContainer />
          </UserProvider>
          <LoadingSpinner />
        </Provider>
      </>
  )
}

export default App
