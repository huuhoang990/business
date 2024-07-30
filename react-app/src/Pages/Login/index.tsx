import { useAuth } from "@/Context/userAuth";
import { useForm, Resolver } from "react-hook-form"
import { yupResolver } from "@hookform/resolvers/yup"
import * as Yup from "yup"

type Props = {}

type LoginFormsInputs = {
  userName: string
  password: string
}

const validation = Yup.object().shape({
  userName: Yup.string().required("Username is required"),
  password: Yup.string().required("Password is required")
})

const Login = (props: Props) => {
  const { loginUser } = useAuth();
  const { register, handleSubmit, formState: { errors }} = useForm<LoginFormsInputs>({ resolver: yupResolver(validation) })

  const handleLogin = (form: LoginFormsInputs) => {
    loginUser(form.userName, form.password)
  }

  return (
    <div id="inner-content" className="my-5 w-100 justify-content-center align-self-center">
      <div className="row">
        <div className="col">
          <div className="card">
            <div className="card-body p-4 p-md-5">
              <h3 className="mb-4 pb-2 pb-md-0 mb-md-5">Login</h3>
              <form onSubmit={ handleSubmit(handleLogin) }>
                <div className="row mt-4">
                  <div className="col-12">
                    <div className="form-outline">
                      <label className="form-label" htmlFor="userName">Username</label>
                      <input
                        type="text"
                        id="userName"
                        className="form-control form-control-lg"
                        {...register("userName")}
                      />
                      { errors.userName ? <p>{ errors.userName.message }</p> : "" }
                    </div>
                  </div>
                </div>
                <div className="row mt-4">
                  <div className="col-12">
                    <div className="form-outline">
                      <label className="form-label" htmlFor="password">Password</label>
                      <input
                        type="text"
                        id="password"
                        className="form-control form-control-lg"
                        {...register("password")}
                      />
                      { errors.password ? <p>{ errors.password.message }</p> : "" }
                    </div>
                  </div>
                </div>
                <div className="row mt-4">
                  <div className="col-12">
                    <input data-mdb-ripple-init className="btn btn-primary btn-lg" type="submit" value="Submit" />
                    <p className="mt-1">Don't have an account? <a className="ms-lg-1" href="login.html">Sign up</a></p>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Login