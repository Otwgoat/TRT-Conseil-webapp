import React, { useEffect, useRef, useState } from "react";
import { Header } from "../components/Header";
import { FieldGroup } from "../components/FieldGroup";
import userApi from "../services/userApi";

export const RegisterPage = () => {
  const formRef = useRef(formRef);
  const [errors, setErrors] = useState({
    email: "",
    password: "",
    firstName: "",
    lastName: "",
    birthDate: "",
    job: "",
    companyName: "",
    companyAddress: "",
  });
  const [role, setRole] = useState("candidate");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState();
  const [confirmPassword, setConfirmPassword] = useState("");
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastname] = useState("");
  const [birthDate, setBirthDate] = useState();
  const [job, setJob] = useState("");
  const [companyName, setCompanyName] = useState("");
  const [companyAddress, setCompanyAddress] = useState("");
  const approved = false;
  let userData = {};

  const roleHandleChange = (e) => {
    setRole(e.target.value);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (role === "candidate" && password && password === confirmPassword) {
      userData = {
        role: role,
        email: email,
        password: password,
        firstName: firstName,
        lastName: lastName,
        birthDate: birthDate,
        job: job,
        approved: approved,
      };
    } else if (
      role === "recruiter" &&
      password &&
      password === confirmPassword
    ) {
      userData = {
        role: role,
        email: email,
        password: password,
        companyName: companyName,
        companyAdress: companyAddress,
        approved: approved,
      };
    } else if (password !== confirmPassword) {
      setErrors({ password: "Les mots de passe ne sont pas identiques" });
      return;
    } else {
      setErrors({ password: "Veuillez renseigner un mot de passe" });
      return;
    }
    try {
      await userApi.createUser(userData).then((response) => {
        console.log(response);
        setErrors({});
        formRef.current.reset();
      });
    } catch (error) {
      console.log(error);
      if (error.response && error.response.data) {
        const violations = error.response.data.violations;
        console.log(violations);
        if (violations) {
          const apiErrors = {};
          violations.forEach(({ propertyPath, title }) => {
            apiErrors[propertyPath] = title;
          });
          setErrors(apiErrors);
        }
      }
    }
  };

  return (
    <div className="container">
      <Header />
      <main id="registerPage">
        <form ref={formRef} onSubmit={handleSubmit}>
          <label htmlFor="role">Vous êtes :</label>
          <select name="role" id="role" onChange={roleHandleChange}>
            <option value="candidate">Candidat</option>
            <option value="recruiter">Recruteur</option>
          </select>
          <FieldGroup
            id="username"
            label="Adresse email"
            type="email"
            placeholder="Votre adresse email"
            onChange={(e) => setEmail(e.target.value)}
            error={errors.email}
          />
          <FieldGroup
            id="password"
            label="Mot de passe"
            type="password"
            placeholder="Votre mot de passe"
            onChange={(e) => setPassword(e.target.value)}
            error={errors.password}
          />
          <FieldGroup
            id="passwordConfirm"
            label="Confirmation du mot de passe"
            type="password"
            placeholder="Confirmez votre mot de passe"
            onChange={(e) => setConfirmPassword(e.target.value)}
          />
          {role === "candidate" ? (
            <>
              <FieldGroup
                id="firstName"
                label="Prénom"
                type="text"
                placeholder="Votre prénom"
                onChange={(e) => setFirstName(e.target.value)}
                error={errors.firstName}
              />
              <FieldGroup
                id="lastName"
                label="Nom"
                type="text"
                placeholder="Votre nom"
                onChange={(e) => setLastname(e.target.value)}
                error={errors.lastName}
              />
              <label htmlFor="birthDate">Date de naissance</label>
              <input
                type="date"
                name="birthDate"
                id="birthDate"
                onChange={(e) => setBirthDate(e.target.value)}
              />
              {errors.birthdate && <p>{errors.birthdate}</p>}
              <FieldGroup
                id="job"
                label="Métier"
                type="text"
                placeholder="Votre métier"
                onChange={(e) => setJob(e.target.value)}
                error={errors.job}
              />
            </>
          ) : (
            <>
              <FieldGroup
                id="companyName"
                label="Nom de l'entreprise"
                type="text"
                placeholder="Nom de l'entreprise"
                onChange={(e) => setCompanyName(e.target.value)}
                error={errors.companyName}
              />
              <FieldGroup
                id="companyAddress"
                label="Adresse de l'entreprise"
                type="text"
                placeholder="Adresse de l'entreprise"
                onChange={(e) => setCompanyAddress(e.target.value)}
                error={errors.companyAddress}
              />
            </>
          )}
          <button type="submit" className="ctaButton" onSubmit={handleSubmit}>
            S'inscrire
          </button>
        </form>
      </main>
    </div>
  );
};
