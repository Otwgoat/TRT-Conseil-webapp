import React, { useEffect, useState } from "react";
import advertissementApi from "../services/advertissementApi";
import Button from "../components/Button";
import applicationsApi from "../services/applicationsApi";
import userApi from "../services/userApi";
import authApi from "../services/authApi";
import { Header } from "../components/Header";
import { GetApplications } from "./dashboard/recruiter/GetApplications";

export const AdDescriptionPage = () => {
  const [ad, setAd] = useState();
  const [currentUser, setCurrentUser] = useState({});
  const [isUserApproved, setIsUserApproved] = useState(false);
  const [applicationSuccess, setApplicationSuccess] = useState(false);
  const [displayApplications, setDisplayApplications] = useState(false);
  const reformatedDate = (datestring) => {
    const dateStr = datestring;
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0"); // Les mois sont indexés à partir de 0 en JavaScript
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
  };
  const userRole = authApi.getUserType();
  useEffect(() => {
    const pathname = window.location.pathname;
    const id = pathname.substring(pathname.lastIndexOf("/") + 1);
    const fetchData = async () => {
      const data = await advertissementApi.findOneAdvertissement(id);

      setAd(data);
    };
    fetchData();
  }, []);

  const sendApplication = async (id) => {
    let data = {
      jobID: id,
      approved: false,
    };
    try {
      await applicationsApi.sendApplication(data);
      setApplicationSuccess(true);
    } catch (error) {}
  };

  useEffect(() => {
    const fetchData = async () => {
      const data = await userApi.getUser();
      setCurrentUser(data);
      console.log(data);
    };
    fetchData();
  }, []);

  useEffect(() => {
    if (currentUser.approved) {
      setIsUserApproved(true);
    }
  }, [currentUser]);

  useEffect(() => {
    if (ad) {
      const fetchData = async () => {
        const data = await applicationsApi.getApplicationByJobAndUser(ad.id);
        if (data > 0) {
          setApplicationSuccess(true);
        }
      };
      fetchData();
    }
  }, [ad]);

  return (
    <div className="container">
      <Header />
      {ad ? (
        <main id="adDescriptionPage">
          <div
            className={
              displayApplications
                ? "adDescriptionInactive"
                : "adDescriptionContainer"
            }
          >
            <h2>{ad.title}</h2>
            <div id="descriptionHeader">
              <p>
                Lieu: <span>{ad.city}</span>
              </p>
              {ad.recruiterId ? (
                <p>
                  Employeur: <span>{ad.recruiterId.companyName}</span>
                </p>
              ) : (
                <p>Recruteur inconnu</p>
              )}
            </div>
            <div id="descriptionBody">
              <p>{ad.description}</p>
            </div>

            <div id="descriptionFooter">
              <p>
                Type de contrat: <span>{ad.type}</span>
              </p>
              <p>
                Salaire: <span>{ad.salary}€</span>
              </p>
              <p>
                Date de début: <span>{reformatedDate(ad.startDate)}</span>
              </p>
              {ad.endDate && (
                <p>
                  Date de fin: <span>{reformatedDate(ad.endDate)}</span>
                </p>
              )}
            </div>
            {userRole === "recruiter" &&
            currentUser.id === ad.recruiterId.id ? (
              <>
                <button
                  className="ctaButton"
                  onClick={() => {
                    setDisplayApplications(!displayApplications);
                  }}
                >
                  Consulter les candidatures
                </button>
              </>
            ) : userRole === "candidate" && isUserApproved ? (
              !applicationSuccess ? (
                <button
                  onClick={() => sendApplication(ad.id)}
                  type="submit"
                  id="applyButton"
                  className="ctaButton"
                >
                  Postuler
                </button>
              ) : (
                <p className="succesMessage">Candidature envoyée</p>
              )
            ) : (
              <p className="errorMessage">
                Cette annonce n'est consultable qu'en lecture seule par votre
                compte.
              </p>
            )}
          </div>
          {displayApplications && (
            <GetApplications
              jobID={ad.id}
              className={
                displayApplications
                  ? "applicationsContainerActive"
                  : "inactiveContainer"
              }
              onClick={() => {
                setDisplayApplications(!displayApplications);
              }}
            />
          )}
        </main>
      ) : (
        <>
          <h1>Aucune annonce n'a été trouvée.</h1>
          <Button path={"/annonces"} title="Retour aux annonces" />
        </>
      )}
    </div>
  );
};
