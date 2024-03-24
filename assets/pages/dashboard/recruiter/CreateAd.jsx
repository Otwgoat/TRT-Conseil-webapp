import React, { useEffect, useRef, useState } from "react";
import { FieldGroup } from "../../../components/FieldGroup";
import advertissementApi from "../../../services/advertissementApi";
import userApi from "../../../services/userApi";
export const CreateAd = () => {
  const formRef = useRef(formRef);
  const [errors, setErrors] = useState({
    title: "",
    description: "",
    city: "",
    planning: "",
    salary: "",
    startDate: "",
  });
  const [contractType, setContractType] = useState("CDI");
  const [jobTitle, setJobTitle] = useState("");
  const [jobDescription, setJobDescription] = useState("");
  const [jobLocation, setJobLocation] = useState("");
  const [jobPlanning, setJobPlanning] = useState("");
  const [jobSalary, setJobSalary] = useState();
  const [startDate, setStartDate] = useState("");
  const [endDate, setEndDate] = useState("");
  const [isUserApproved, setIsUserApproved] = useState(false);
  const [currentUser, setCurrentUser] = useState();
  const contractHandleChange = (e) => {
    setContractType(e.target.value);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    let parsedSalary = parseInt(jobSalary);
    let data = {
      title: jobTitle,
      description: jobDescription,
      city: jobLocation,
      planning: jobPlanning,
      salary: parsedSalary,
      type: contractType,
      startDate: startDate,
      approved: false,
    };
    if (endDate) {
      data.endDate = endDate;
      if (startDate < endDate) {
        setErrors({ ...errors, endDate: "" });
      } else {
        setErrors({
          ...errors,
          endDate: "La date de fin doit être après la date de début",
        });
        return;
      }
    }
    if (startDate < new Date().toISOString().split("T")[0]) {
      setErrors({
        ...errors,
        startDate: "La date de début doit être après aujourd'hui",
      });
      return;
    }
    try {
      await advertissementApi.postAdvertissement(data).then((response) => {
        setErrors({});
        formRef.current.reset();
      });
      setErrors({});
    } catch (error) {
      if (error.response && error.response.data) {
        const violations = error.response.data.violations;

        // Check if there's violation(s) and assign them to the properties in error's useState
        //====================================================================================
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

  useEffect(() => {
    const fetchData = async () => {
      const data = await userApi.getUser();
      setCurrentUser(data);
    };
    fetchData();
  }, []);

  useEffect(() => {
    if (currentUser && currentUser.approved) {
      setIsUserApproved(true);
    }
  }, [currentUser]);

  return (
    <div className="dashboardContent">
      <h2>Créer une annonce</h2>
      {isUserApproved ? (
        <form ref={formRef} onSubmit={handleSubmit}>
          <FieldGroup
            id="jobTitle"
            label="Intitulé du poste"
            type="text"
            placeholder="Entrez l'intitulé du poste"
            error={errors.title}
            onChange={(e) => setJobTitle(e.target.value)}
          />
          <FieldGroup
            id="jobDescription"
            label="Description du poste"
            type="textarea"
            placeholder="Entrez la description du poste"
            error={errors.description}
            onChange={(e) => setJobDescription(e.target.value)}
          />
          <FieldGroup
            id="jobLocation"
            label="Lieu de travail"
            type="text"
            placeholder="Entrez le lieu de travail"
            error={errors.city}
            onChange={(e) => setJobLocation(e.target.value)}
          />
          <FieldGroup
            id="jobPlanning"
            label="Nombre d'heures par semaine"
            type="text"
            placeholder="Entrez le nombre d'heure par semaine"
            error={errors.planning}
            onChange={(e) => setJobPlanning(e.target.value)}
          />
          <FieldGroup
            id="jobSalary"
            label="Salaire"
            type="number"
            placeholder="Entrez le salaire"
            error={errors.salary}
            onChange={(e) => setJobSalary(e.target.value)}
          />
          <label id="contractLabel" htmlFor="contractType">
            Type de contrat
          </label>
          <select
            name="contractType"
            id="contractType"
            onChange={contractHandleChange}
          >
            <option value="CDI">CDI</option>
            <option value="CDD">CDD</option>
            <option value="Stage">Stage</option>
            <option value="Alternance">Alternance</option>
          </select>
          <FieldGroup
            id="startDate"
            label="Date de début"
            type="date"
            onChange={(e) => setStartDate(e.target.value)}
            error={errors.startDate}
          />
          {contractType !== "CDI" && (
            <FieldGroup
              id="endDate"
              label="Date de fin"
              type="date"
              onChange={(e) => setEndDate(e.target.value)}
              error={errors.endDate}
            />
          )}
          <button type="submit" className="ctaButton" onSubmit={handleSubmit}>
            Créer l'annonce
          </button>
        </form>
      ) : (
        <p>
          Votre compte doit être approuvé avant de pouvoir soumettre une offre
          d'emploi.
        </p>
      )}
    </div>
  );
};
