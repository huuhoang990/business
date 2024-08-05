import { createBrowserRouter } from 'react-router-dom'
import OneColumnContentLayout from '@/Layout/OneColumnContentLayout'
import TwoColumnContentLayout from '@/Layout/TwoColumnContentLayout'
import Register from '@/Pages/Register'
import Login from '@/Pages/Login'
import Home from '@/Pages/Home'
import ProtectedRoute from '@/Routes/ProtectedRoute'

export const router = createBrowserRouter([
  {
    path: '/register',
    element: <TwoColumnContentLayout></TwoColumnContentLayout>, 
    children: [
      {
        index: true,
        element: <Register></Register>
      },
    ]
  },
  {
    path: '/login',
    element: <OneColumnContentLayout></OneColumnContentLayout>,
    children: [
      {
        index: true,
        element: <Login></Login>
      },
    ]
  },
  {
    path: '/',
    // element: <OneColumnContentLayout></OneColumnContentLayout>,
    children: [
      {
        index: true,
        element: <ProtectedRoute><Home/></ProtectedRoute>
      },
    ]
  }
])
/*
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
*/
