import { registerUserApi, getProvincesApi } from "@/Service/RegisterService"
import { useEffect, useState } from "react"
import { Province } from "@/Types/Province"
import { RegisterForm } from "@/Types/Forms/RegisterForm"
import * as Yup from "yup"
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup"

const validation = Yup.object().shape({
  email: Yup.string().required("Email is required").email("Invalid email format"),
  password: Yup.string().required("Password is required"),
  firstName: Yup.string().required("First name is required"),
  lastName: Yup.string().required("Last name is required"),
  birthday: Yup.string().required("Birthday name is required"),
  genderId: Yup.string().required("Gender is required"),
  phoneNumber: Yup.string().required("Phone number is required"),
  provinceId: Yup.string().required("City is required"),
  districtId: Yup.string().required("District is required"),
  wardId: Yup.string().required("Ward is required"),
  street: Yup.string().required("Street is required")
})

const Register = () => {
  const [provinceList, setProvinceList] = useState<Province[]>([]);
  const [formState, setFormState] = useState<RegisterForm>({
    email: '',
    password: '',
    firstName: '',
    lastName: '',
    birthday: '',
    genderId: '',
    phoneNumber: '',
    provinceId: '',
    districtId: '',
    wardId: '',
    street: ''
  });

  const {
    register,
    handleSubmit,
    trigger,
    formState: { errors, isValid, touchedFields },
  } = useForm<RegisterForm>({ resolver: yupResolver(validation) });

  const handleRegister = async (form: RegisterForm) => {
    await registerUserApi(form)
  }

  useEffect(() => {
    const fetchProvices = async () => {
      const data = await getProvincesApi()
      setProvinceList(data)
    }

    fetchProvices()

    return () => {
      // any cleanup logic if needed
    }
  }, [])

  const getValidOrInvalidClass = (fieldName: keyof RegisterForm) => {
    if (touchedFields[fieldName]) {
      if (errors[fieldName]) {
        return 'is-invalid';
      } else {
        return 'is-valid';
      }
    }
    return '';
  };

  const handleBlur = (field: keyof RegisterForm) => {
    trigger(field)
  };

  return (
    <div id="inner-content" className="my-5 w-100 justify-content-center align-self-center">
      <div className="row">
        <div className="col">
          <div className="card">
            <div className="card-body p-4 p-md-5">
              <h3 className="mb-4 pb-2 pb-md-0 mb-md-5">Registration Form</h3>
              <form
                onSubmit={ handleSubmit(handleRegister) }
                className={`needs-validation ${isValid ? 'was-validated' : ''}`}
                noValidate>
                <div className="row mt-4">
                  <div className="col-6">
                    <div className="form-outline has-validation">
                      <label className="form-label" htmlFor="emailAddress">Email</label>
                      <input
                        type="text"
                        id="emailAddress"
                        className={`form-control form-control-lg ${getValidOrInvalidClass('email')}`}
                        {...register("email", {
                          onBlur: () => {
                            trigger('email');
                          }
                        })} />
                        { errors.email ? <p className="invalid-feedback">{ errors.email.message }</p> : "" }
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-outline">
                      <label className="form-label" htmlFor="password">Passsword</label>
                      <input
                        type="text"
                        id="passsword"
                        className={`form-control form-control-lg ${getValidOrInvalidClass('password')}`}
                        {...register("password", {
                          onBlur: () => {
                            trigger('password');
                          }
                        })} />
                      { errors.password ? <p className="invalid-feedback">{ errors.password.message }</p> : "" }
                    </div>
                  </div>
                </div>
                <div className="row mt-4">
                  <div className="col-6">
                    <div className="form-outline">
                      <label className="form-label" htmlFor="firstName">First Name</label>
                      <input
                        type="text"
                        id="firstName"
                        className={`form-control form-control-lg ${getValidOrInvalidClass('firstName')}`}
                        value={formState.firstName}
                        {...register("firstName", {
                          onBlur: () => {
                            trigger('firstName');
                          }
                        })}
                      />
                        { errors.firstName ? <p className="invalid-feedback">{ errors.firstName.message }</p> : "" }
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-outline">
                      <label className="form-label" htmlFor="lastName">Last Name</label>
                      <input
                      type="text"
                      id="lastName"
                      className={`form-control form-control-lg ${getValidOrInvalidClass('lastName')}`}
                      {...register("lastName", {
                        onBlur: () => {
                          trigger('lastName');
                        }
                      })} />
                      { errors.lastName ? <p className="invalid-feedback">{ errors.lastName.message }</p> : "" }
                    </div>
                  </div>
                </div>
                <div className="row mt-4">
                  <div className="col-6">
                    <div className="form-outline datepicker w-100">
                      <label htmlFor="birthday" className="form-label">Birthday</label>
                      <input
                        type="date"
                        className={`form-control form-control-lg ${getValidOrInvalidClass('birthday')}`}
                        id="birthday"
                        {...register("birthday", {
                          onBlur: () => {
                            trigger('birthday');
                          }
                        })}
                      />
                        { errors.birthday ? <p className="invalid-feedback">{ errors.birthday.message }</p> : "" }
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-outline">
                      <h6 className="mb-2 pb-1">Gender: </h6>
                      <div className="form-check form-check-inline">
                        <input
                          type="radio"
                          className={`form-check-input ${getValidOrInvalidClass('genderId')}`}
                          id="femaleGender"
                          value="2"
                          {...register("genderId", {
                            onBlur: () => {
                              trigger('genderId');
                            }
                          })}
                        />
                        <label className="form-check-label" htmlFor="femaleGender">Female</label>
                      </div>
                      <div className="form-check form-check-inline">
                        <input
                          type="radio"
                          className={`form-check-input ${getValidOrInvalidClass('genderId')}`}
                          id="maleGender"
                          value="1"
                          {...register("genderId", {
                            onBlur: () => {
                              trigger('genderId');
                            }
                          })}
                        />
                        <label className="form-check-label" htmlFor="maleGender">Male</label>
                      </div>
                      <div className="form-check form-check-inline">
                        <input
                          type="radio"
                          className={`form-check-input ${getValidOrInvalidClass('genderId')}`}
                          id="otherGender"
                          value="3"
                          {...register("genderId", {
                            onBlur: () => {
                              trigger('genderId');
                            }
                          })}
                        />
                        <label className="form-check-label" htmlFor="otherGender">Other</label>
                      </div>
                      { errors.genderId ? <p className="invalid-feedback">{ errors.genderId.message }</p> : "" }
                    </div>
                  </div>
                </div>
                <div className="row mt-4">
                  <div className="col-6">
                    <div className="form-outline">
                      <label className="form-label" htmlFor="phoneNumber">Phone Number</label>
                      <input
                        type="tel"
                        id="phoneNumber"
                        className={`form-control form-control-lg ${getValidOrInvalidClass('phoneNumber')}`}
                        {...register("phoneNumber", {
                          onBlur: () => {
                            trigger('phoneNumber');
                          }
                        })}
                      />
                      { errors.phoneNumber ? <p className="invalid-feedback">{ errors.phoneNumber.message }</p> : "" }
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-outline">
                      <label className="form-label select-label">City</label>
                      <select
                        className={`select form-select form-select-lg ${getValidOrInvalidClass('provinceId')}`}
                        value={formState.provinceId}
                        {...register("provinceId", {
                          onBlur: () => {
                            trigger('provinceId');
                          }
                        })}>
                        <option value="" disabled>Select your city</option>
                      </select>
                    </div>
                    { errors.provinceId ? <p className="invalid-feedback">{ errors.provinceId.message }</p> : "" }
                  </div>
                </div>
                <div className="row mt-4">
                  <div className="col-6">
                    <div className="form-outline">
                      <label className="form-label select-label">District</label>
                      <select
                        className={`select form-select form-select-lg ${getValidOrInvalidClass('districtId')}`}
                        {...register("districtId", {
                          onBlur: () => {
                            trigger('districtId');
                          }
                        })}
                      >
                        <option value="1" disabled>Choose option</option>
                        <option value="2">Subject 1</option>
                        <option value="3">Subject 2</option>
                        <option value="4">Subject 3</option>
                      </select>
                      { errors.districtId ? <p className="invalid-feedback">{ errors.districtId.message }</p> : "" }
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-outline">
                      <label className="form-label select-label">Ward</label>
                      <select
                        className={`select form-select form-select-lg ${getValidOrInvalidClass('wardId')}`}
                        {...register("wardId", {
                          onBlur: () => {
                            trigger('wardId');
                          }
                        })}
                      >
                        <option value="1" disabled>Choose option</option>
                        <option value="2">Subject 1</option>
                        <option value="3">Subject 2</option>
                        <option value="4">Subject 3</option>
                      </select>
                      { errors.wardId ? <p className="invalid-feedback">{ errors.wardId.message }</p> : "" }
                    </div>
                  </div>
                </div>
                <div className="row mt-4">
                  <div className="col-12">
                    <div className="form-outline">
                      <label className="form-label" htmlFor="phoneNumber">Street</label>
                      <input
                        type="text"
                        id="street"
                        className={`form-control form-control-lg ${getValidOrInvalidClass('street')}`}
                        {...register("street", {
                          onBlur: () => {
                            trigger('street')
                          }
                        })} />
                      { errors.street ? <p className="invalid-feedback">{ errors.street.message }</p> : "" }
                    </div>
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
    </div>
  );
}

export default Register;