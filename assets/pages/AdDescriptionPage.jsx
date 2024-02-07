import React, { useEffect, useState } from "react";
import advertissementApi from "../services/advertissementApi";
import Button from "../components/Button";
import applicationsApi from "../services/applicationsApi";
import userApi from "../services/userApi";
import authApi from "../services/authApi";

export const AdDescriptionPage = () => {
  const [ad, setAd] = useState([]);
  const [currentUser, setCurrentUser] = useState({});
  const [isUserApproved, setIsUserApproved] = useState(false);
  const [applicationSuccess, setApplicationSuccess] = useState(false);

  const userRole = authApi.getUserType();
  useEffect(() => {
    const pathname = window.location.pathname;
    const id = pathname.substring(pathname.lastIndexOf("/") + 1);
    const fetchData = async () => {
      const data = await advertissementApi.findOneAdvertissement(id);
      console.log(data);
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
    } catch (error) {
      console.log(error);
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
    if (currentUser.approved) {
      setIsUserApproved(true);
    }
  }, [currentUser]);

  return (
    <div className="container">
      {ad ? (
        <main id="adDescriptionPage">
          <div className="adDescriptionContainer">
            <div id="descriptionHeader">
              <h1>{ad.title}</h1>
              <p>{ad.city}</p>
              {ad.recruiterId ? (
                <p>{ad.recruiterId.companyName}</p>
              ) : (
                <p>Recruteur inconnu</p>
              )}
            </div>
            <div id="descriptionBody">
              <p>{ad.description}</p>
            </div>
            <div id="descriptionFooter">
              <p>Type de contrat: {ad.type}</p>
              <p>Salaire: {ad.salary}€</p>
              <p>Date de début: {ad.startDate}</p>
              {ad.endDate && <p>Date de fin: {ad.endDate}</p>}
            </div>
            {userRole === "recruiter" ? (
              <p>Consulter les candidatures</p>
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
                <p>Candidature déjà envoyée.</p>
              )
            ) : (
              <p>
                Votre compte n'a pas encore été approuvé par un administrateur.
                Veuillez patienter avant de postuler à cette annonce.
              </p>
            )}

            {/*{userRole === "candidate" && isUserApproved ? (
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
                <p>Candidature déjà envoyée pour cette annonce</p>
              )
            ) : (
              <p>
                Votre compte n'a pas encore été approuvé par un administrateur.
                Veuillez patienter avant de postuler à cette annonce.
              </p>
            )}*/}
          </div>
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
