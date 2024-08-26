import { registerUserApi, getProvincesApi, getDistrictByProvinceIdApi, getWardByDistrictIdApi } from "@/Service/RegisterService"
import { useEffect, useState } from "react"
import { Province } from "@/Types/Province"
import { District } from "@/Types/District"
import { Ward } from "@/Types/Ward"
import { RegisterForm } from "@/Types/Forms/RegisterForm"
import * as Yup from "yup"
import { useForm } from "react-hook-form"
import { yupResolver } from "@hookform/resolvers/yup"
import { useDispatch } from 'react-redux'
import { showLoading, hideLoading } from '@/Store/loadingSlice'
import { useNavigate } from "react-router-dom"

const validation = Yup.object().shape({
  email: Yup.string().required("Email is required").email("Invalid email format"),
  password: Yup.string().required("Password is required")
    .min(8, 'Password must contain at least 8 character')
    .max(20, 'Password cannot exceed 20 characters')
    .matches(/[0-9]/, 'Password requires a number')
    .matches(/[a-z]/, 'Password requires a lowercase letter')
    .matches(/[A-Z]/, 'Password requires an uppercase letter')
    .matches(/[^\w]/, 'Password requires a symbol'),
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
  const navigate = useNavigate()
  const dispatch = useDispatch();
  const [provinceList, setProvinceList] = useState<Province[]>([])
  const [districtList, setDistrictList] = useState<District[]>([])
  const [wardList, setWardList] = useState<Ward[]>([])

  const [formState] = useState<RegisterForm>({
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
    formState: { errors, isValid, touchedFields, submitCount },
  } = useForm<RegisterForm>({ resolver: yupResolver(validation) });

  const handleRegister = async (form: RegisterForm) => {
    dispatch(showLoading());
    try {
      await registerUserApi(form)
      navigate("/login")
    } finally {
      dispatch(hideLoading());
    }
  }

  useEffect(() => {
    const fetchProvices = async () => {
      dispatch(showLoading());
      try {
        const data = await getProvincesApi()
        setProvinceList(data)
      } finally {
        dispatch(hideLoading());
      }
    }
    fetchProvices()
  }, [])

  const getValidOrInvalidClass = (fieldName: keyof RegisterForm) => {
    if (touchedFields[fieldName] || submitCount > 0) {
      if (errors[fieldName]) {
        return 'is-invalid';
      } else {
        return 'is-valid';
      }
    }
    return '';
  };

  const fetchDistrictsByProviceId = async (provinceId: string) => {
    dispatch(showLoading());
    try {
      const data = await getDistrictByProvinceIdApi(provinceId)
      setDistrictList(data)
    } finally {
      dispatch(hideLoading());
    }
  }

  const fetchWardsByDistrictId = async (districtId: string) => {
    dispatch(showLoading());
    try {
      const data = await getWardByDistrictIdApi(districtId)
      setWardList(data)
    } finally {
      dispatch(hideLoading());
    }
  }

  const renderProvinceOptions = () : JSX.Element[] => {
    const listProvinceRender: JSX.Element[] = []

    provinceList.forEach(province => {
      listProvinceRender.push(
        <option key={province.id} value={province.id}>
          {province.name}
        </option>
      );
    })
    return listProvinceRender
  }

  const renderDistrictOptions = () : JSX.Element[] => {
    const listDistrictRender: JSX.Element[] = []

    districtList.forEach(district => {
      listDistrictRender.push(
        <option key={district.id} value={district.id}>
          {district.name}
        </option>
      );
    })
    return listDistrictRender
  }

  const renderWardOptions = () : JSX.Element[] => {
    const listWardRender: JSX.Element[] = []

    wardList.forEach(ward => {
      listWardRender.push(
        <option key={ward.id} value={ward.id}>
          {ward.name}
        </option>
      );
    })
    return listWardRender
  }

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
                        {...register("provinceId", {
                          onBlur: () => {
                            trigger('provinceId');
                          },
                          onChange: (event) => {
                            fetchDistrictsByProviceId(event.target.value)
                          }
                        })}>
                        <option value="" disabled selected>Select your city</option>
                        {provinceList.length > 0 ? renderProvinceOptions() : null}
                      </select>
                      { errors.provinceId ? <p className="invalid-feedback">{ errors.provinceId.message }</p> : "" }
                    </div>
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
                          },
                          onChange: (event) => {
                            fetchWardsByDistrictId(event.target.value)
                          }
                        })}
                      >
                        <option value="" disabled selected>Select your district</option>
                        {districtList.length > 0 ? renderDistrictOptions() : null}
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
                        <option value="" disabled selected>Select your ward</option>
                        {wardList.length > 0 ? renderWardOptions() : null}
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
                  <input className="btn btn-primary btn-lg" type="submit" value="Submit" />
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