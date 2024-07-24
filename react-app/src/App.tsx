import { RouterProvider } from 'react-router-dom'
import { router } from "@/Routes/Routes";
import '@/assets/css/style.scss'
import 'bootstrap/dist/css/bootstrap.min.css'

function App() {
  return (
      <>
        <RouterProvider router={router} />
      </>
  )
}

export default App
