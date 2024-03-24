import React, { useEffect, useState } from "react";
import advertissementApi from "../../../services/advertissementApi";
import { Link } from "react-router-dom";
import Button from "../../../components/Button";
import applicationsApi from "../../../services/applicationsApi";

export const GetAdvertissements = () => {
  const [advertissements, setAdvertissements] = useState([]);

  useEffect(() => {
    const fetchAdvertissements = async () => {
      await advertissementApi.getMyAdvertissements().then((data) => {
        setAdvertissements(data);
      });
    };
    fetchAdvertissements();
  }, []);
  const handleDelete = async (id) => {
    await advertissementApi.deleteMyAdvertissement(id).then((data) => {});
  };
  return (
    <div className="dashboardContent">
      <h2>Mes annonces</h2>
      {advertissements.length <= 0 && (
        <p>Aucune annonce publiée ou approuvée</p>
      )}
      <div className="contentContainer">
        {advertissements &&
          advertissements.map((advertissement) => (
            <div key={advertissement.id} className="contentItem">
              <h3 className="contentTitle">{advertissement.title}</h3>
              <p id="adCity"> {advertissement.city}</p>
              <p>{advertissement.description}</p>
              <p>
                {advertissement.approved
                  ? "Status: Approuvée"
                  : "Status: En attente d'approbation"}
              </p>
              <div className="contentCtas">
                <Button
                  path={`/annonce/${advertissement.id}`}
                  title="Voir mon annonce"
                />
                <button
                  className="ctaButton redButton"
                  onClick={() => handleDelete(advertissement.id)}
                >
                  Supprimer l'annonce
                </button>
              </div>
            </div>
          ))}
      </div>
    </div>
  );
};
