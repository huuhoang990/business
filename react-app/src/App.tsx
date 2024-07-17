import { RouterProvider } from 'react-router-dom'
import '@/assets/css/style.scss'
import { router } from "@/Routes/Routes";

function App() {
  return (
      <>
        <RouterProvider router={router} />
      </>
  )
}

export default App
