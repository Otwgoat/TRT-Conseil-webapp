import React, { useEffect, useState } from "react";
import authApi from "../../services/authApi";
import userApi from "../../services/userApi";
import { FieldGroup } from "../../components/FieldGroup";

export const PersonalInfos = () => {
  const [currentUser, setCurrentUser] = useState({});
  const [userRole, setUserRole] = useState("");
  const [email, setEmail] = useState(currentUser.email);
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [birthDate, setBirthDate] = useState("");
  const [job, setJob] = useState("");
  const [companyName, setCompanyName] = useState("");
  const [companyAddress, setCompanyAddress] = useState("");
  let userData = {};
  const handleSubmit = async (e) => {
    e.preventDefault();
    if (userRole === "candidate") {
      userData = {
        role: "candidate",
        email: email,
        firstName: firstName,
        lastName: lastName,
        birthDate: birthDate,
        job: job,
      };
    } else {
      userData = {
        role: "recruiter",
        email: email,
        companyName: companyName,
        companyAdress: companyAddress,
      };
    }
    try {
      await userApi.updateUser(userData, currentUser.id);
    } catch (error) {
      console.log(error);
    }
  };

  useEffect(() => {
    const role = authApi.getUserType();
    setUserRole(role);
    if (currentUser.birthdate) {
      const birthdate = new Date(currentUser.birthdate);
      const year = birthdate.getFullYear();
      const month = String(birthdate.getMonth() + 1).padStart(2, "0");
      const day = String(birthdate.getDate()).padStart(2, "0");
      const reformatedBirthdate = `${year}-${month}-${day}`;
      setBirthDate(reformatedBirthdate);
    }
    setEmail(currentUser.email);
    setFirstName(currentUser.firstName);
    setLastName(currentUser.lastName);
    setJob(currentUser.job);
    setCompanyName(currentUser.companyName);
    setCompanyAddress(currentUser.companyAddress);
  }, [currentUser]);

  useEffect(() => {
    const fetchData = async () => {
      const data = await userApi.getUser();
      console.log(data);
      setCurrentUser(data);
    };
    fetchData();
  }, []);

  return (
    <div className="personalInfosContainer dashboardContent">
      <h2>Mes informations personelles</h2>
      <form onSubmit={handleSubmit}>
        <FieldGroup
          id="username"
          label="Adresse email"
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
        {userRole === "candidate" ? (
          <>
            <FieldGroup
              id="firstName"
              label="Prénom"
              type="text"
              value={firstName}
              onChange={(e) => setFirstName(e.target.value)}
            />
            <FieldGroup
              id="lastName"
              label="Nom"
              type="text"
              value={lastName}
              onChange={(e) => setLastName(e.target.value)}
            />
            <FieldGroup
              id="birthdate"
              label="Date de naissance"
              type="date"
              value={birthDate}
              onChange={(e) => setBirthDate(e.target.value)}
            />
            <FieldGroup
              id="job"
              label="Métier"
              type="text"
              value={job}
              onChange={(e) => setJob(e.target.value)}
            />
          </>
        ) : (
          <>
            <FieldGroup
              id="companyName"
              label="Nom de l'entreprise"
              type="text"
              value={companyName}
              onChange={(e) => setCompanyName(e.target.value)}
            />
            <FieldGroup
              id="companyAddress"
              label="Adresse de l'entreprise"
              type="text"
              value={companyAddress}
              onChange={(e) => setCompanyAddress(e.target.value)}
            />
          </>
        )}
        <button type="submit" className="ctaButton" onSubmit={handleSubmit}>
          Modifier mes informations
        </button>
      </form>
    </div>
  );
};
