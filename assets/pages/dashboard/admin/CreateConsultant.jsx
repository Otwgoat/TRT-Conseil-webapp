import React, { useRef, useState } from "react";
import { FieldGroup } from "../../../components/FieldGroup";
import adminApi from "../../../services/adminApi";

export const CreateConsultant = () => {
  const [succesMessage, setSuccesMessage] = useState();
  const [email, setEmail] = useState();
  const [firstName, setFirstName] = useState();
  const [lastName, setLastName] = useState();
  const [password, setPassword] = useState("");
  const [role, setRole] = useState("consultant");
  const [errors, setErrors] = useState({
    email: "",

    firstName: "",
    lastName: "",
  });

  const formRef = useRef(formRef);
  const handleSubmit = async (e) => {
    e.preventDefault();
    let data = {};

    data = {
      email: email,
      firstName: firstName,
      lastName: lastName,

      role: role,
    };

    try {
      await adminApi.createConsultant(data).then(() => {
        setErrors({});
        formRef.current.reset();
        setSuccesMessage("Consultant créé avec succès.");
      });
    } catch (error) {
      if (error.response && error.response.data) {
        const violations = error.response.data.violations;

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
    <div className="dashboardContent">
      <h2>Créer un consultant</h2>
      <form ref={formRef} onSubmit={handleSubmit}>
        <FieldGroup
          id="username"
          label="Adresse email"
          type="email"
          value={email}
          error={errors.email}
          onChange={(e) => setEmail(e.target.value)}
        />
        <FieldGroup
          id="firstName"
          label="Prénom"
          type="text"
          value={firstName}
          error={errors.firstName}
          onChange={(e) => setFirstName(e.target.value)}
        />
        <FieldGroup
          id="lastName"
          label="Nom"
          type="text"
          value={lastName}
          error={errors.lastName}
          onChange={(e) => setLastName(e.target.value)}
        />

        {succesMessage && <p className="succesMessage">{succesMessage}</p>}
        <button className="ctaButton" onSubmit={handleSubmit} type="submit">
          Créer
        </button>
      </form>
    </div>
  );
};
