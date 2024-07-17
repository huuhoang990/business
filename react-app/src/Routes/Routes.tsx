import { createBrowserRouter } from 'react-router-dom'
import MainLayout from '@/Layout/MainLayout'
import Register from '@/Pages/Register'
import Login from '@/Pages/Login'
import Home from '@/Pages/Home'

export const router = createBrowserRouter([
  {
    path: '/',
    element: <MainLayout></MainLayout>,
    children: [
      {
        path: '/',
        element: <Home></Home>
      },
      {
        path: '/register',
        element: <Register></Register>
      },
      {
        path: '/login',
        element: <Login></Login>
      },
    ]
  }
])
