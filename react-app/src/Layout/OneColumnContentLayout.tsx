import { Outlet } from "react-router-dom"

const OneColumnContentLayout = () => {
  return (
    <div id="container" className="container-fluid bg-gradient-pink">
        <div id="content-one-col" className="d-flex">
          <Outlet></Outlet>
      </div>
    </div>
  );
}

export default OneColumnContentLayout