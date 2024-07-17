import { Outlet } from "react-router-dom"

const MainLayout = () => {
  return (
    <div id="container">
      <div id="content">
        <div id="inner-content">
          <Outlet></Outlet>
        </div>
      </div>
    </div>
  );
}

export default MainLayout