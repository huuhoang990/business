import { useAuth } from "@/Context/userAuth";
import { useForm } from "react-hook-form"
import { yupResolver } from "@hookform/resolvers/yup"
import { LoginForm } from "@/Types/Forms/LoginForm"
import * as Yup from "yup"

// type Props = {}

const validation = Yup.object().shape({
  email: Yup.string().required("Email is required"),
  password: Yup.string().required("Password is required")
})

const Login = () => {
  const { loginUser } = useAuth();
  const {
    register,
    handleSubmit,
    trigger,
    formState: { errors, isValid, touchedFields, submitCount }
  } = useForm<LoginForm>({ resolver: yupResolver(validation) })

  const handleLogin = (form: LoginForm) => {
    loginUser(form.email, form.password)
  }

  const getValidOrInvalidClass = (fieldName: keyof LoginForm) => {
    if (touchedFields[fieldName] || submitCount > 0) {
      if (errors[fieldName]) {
        return 'is-invalid';
      } else {
        return 'is-valid';
      }
    }
    return '';
  }

  return (
    <div id="inner-content" className="my-5 w-100 justify-content-center align-self-center">
      <div className="row">
        <div className="col">
          <div className="card">
            <div className="card-body p-4 p-md-5">
              <h3 className="mb-4 pb-2 pb-md-0 mb-md-5">Login</h3>
              <form
                onSubmit={ handleSubmit(handleLogin) }
                className={`needs-validation ${isValid ? 'was-validated' : ''}`}
                noValidate>
                <div className="row mt-4">
                  <div className="col-12">
                    <div className="form-outline">
                      <label className="form-label" htmlFor="email">email</label>
                      <input
                        type="text"
                        id="email"
                        className={`form-control form-control-lg ${getValidOrInvalidClass('email')}`}
                        {...register("email", {
                          onBlur: () => {
                            trigger('email');
                          }
                        })}
                      />
                      { errors.email ? <p id="email_error" className="invalid-feedback">{ errors.email.message }</p> : "" }
                    </div>
                  </div>
                </div>
                <div className="row mt-4">
                  <div className="col-12">
                    <div className="form-outline">
                      <label className="form-label" htmlFor="password">Password</label>
                      <input
                        type="password"
                        id="password"
                        className={`form-control form-control-lg ${getValidOrInvalidClass('password')}`}
                        {...register("password", {
                          onBlur: () => {
                            trigger('password');
                          }
                        })}
                      />
                      { errors.password ? <p id="password_error" className="invalid-feedback">{ errors.password.message }</p> : "" }
                    </div>
                  </div>
                </div>
                <div className="row mt-4">
                  <div className="col-12">
                    <input id="submit" className="btn btn-primary btn-lg" type="submit" value="Submit" />
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