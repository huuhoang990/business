import { Outlet } from "react-router-dom"

const MainLayout = () => {
  return (
    <div className="container">
      <div id="content">
        <div className="row justify-content-center align-items-center h-100">
          <div className="col">
            <div className="card">
              <div className="card-body p-4 p-md-5">
                <h3 className="mb-4 pb-2 pb-md-0 mb-md-5">Registration Form</h3>
                <form>
                  <div className="row">
                    <div className="col-6">
                      <div className="form-outline">
                        <label className="form-label" htmlFor="firstName">First Name</label>
                        <input type="text" id="firstName" className="form-control form-control-lg" />
                      </div>
                    </div>
                    <div className="col-6">
                      <div className="form-outline">
                        <label className="form-label" htmlFor="lastName">Last Name</label>
                        <input type="text" id="lastName" className="form-control form-control-lg" />
                      </div>
                    </div>
                  </div>
                  <div className="row">
                    <div className="col-6 mb-4">
                      <div className="form-outline datepicker w-100">
                        <label htmlFor="birthdayDate" className="form-label">Birthday</label>
                        <input type="calendar" className="form-control form-control-lg" id="birthdayDate" />
                      </div>
                    </div>
                    <div className="col-6 mb-4">
                      <div className="form-outline">
                        <h6 className="mb-2 pb-1">Gender: </h6>
                        <div className="form-check form-check-inline">
                          <input className="form-check-input" type="radio" name="inlineRadioOptions" id="femaleGender"
                            value="option1" checked />
                          <label className="form-check-label" htmlFor="femaleGender">Female</label>
                        </div>
                        <div className="form-check form-check-inline">
                          <input className="form-check-input" type="radio" name="inlineRadioOptions" id="maleGender"
                            value="option2" />
                          <label className="form-check-label" htmlFor="maleGender">Male</label>
                        </div>
                        <div className="form-check form-check-inline">
                          <input className="form-check-input" type="radio" name="inlineRadioOptions" id="otherGender"
                            value="option3" />
                          <label className="form-check-label" htmlFor="otherGender">Other</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div className="row">
                    <div className="col-6 mb-4">
                      <div className="form-outline">
                        <label className="form-label" htmlFor="emailAddress">Email</label>
                        <input type="email" id="emailAddress" className="form-control form-control-lg" />
                      </div>
                    </div>
                    <div className="col-6 mb-4">
                      <div data-mdb-input-init className="form-outline">
                        <label className="form-label" htmlFor="phoneNumber">Phone Number</label>
                        <input type="tel" id="phoneNumber" className="form-control form-control-lg" />
                      </div>
                    </div>
                  </div>
                  <div className="row">
                    <div className="col-12">
                      <label className="form-label select-label">Choose option</label>
                    </div>
                    <div className="col-12">
                      <select className="select form-control-lg w-100">
                        <option value="1" disabled>Choose option</option>
                        <option value="2">Subject 1</option>
                        <option value="3">Subject 2</option>
                        <option value="4">Subject 3</option>
                      </select>
                    </div>
                  </div>
                  <div className="mt-4 pt-2 d-flex align-items-center justify-content-between">
                    <input data-mdb-ripple-init className="btn btn-primary btn-lg" type="submit" value="Submit" />
                    <div className="d-inline-flex">
                      <p className="m-0">Already have an account?</p>
                      <a className="ms-lg-1" href="login.html">Login</a>
                    </div>
                  </div> 
                </form>
              </div>
            </div>
          </div>
        </div>
          <Outlet></Outlet>
      </div>
    </div>
  );
}

export default MainLayout