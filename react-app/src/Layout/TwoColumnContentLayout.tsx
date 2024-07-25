import { Outlet } from "react-router-dom"

const TwoColumnContentLayout = () => {
  return (
    <div id="container" className="container-fluid bg-gradient-pink">
        <div id="content-two-col" className="d-flex">
          <Outlet></Outlet>
      </div>
    </div>
  );
}

export default TwoColumnContentLayout