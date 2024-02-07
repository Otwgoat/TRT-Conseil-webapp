import React, { useEffect, useState } from "react";
import advertissementApi from "../../../services/advertissementApi";
import { Link } from "react-router-dom";

export const GetAdvertissements = () => {
  const [advertissements, setAdvertissements] = useState([]);

  useEffect(() => {
    const fetchAdvertissements = async () => {
      await advertissementApi.findAllAdvertissements().then((data) => {
        setAdvertissements(data);
        console.log(data);
      });
    };
    fetchAdvertissements();
  }, []);

  return (
    <div className="dashboardContent">
      <h2>Mes annonces</h2>
      {advertissements.length <= 0 && (
        <p>Aucune annonce publiée ou approuvée</p>
      )}
      {advertissements &&
        advertissements.map((advertissement) => (
          <div key={advertissement.id} className="advertissementItem">
            <h3>{advertissement.title}</h3>
            <p>{advertissement.city}</p>
            <p>{advertissement.recruiterId.companyName}</p>
            <Link to={`/annonce/${advertissement.id}`}>Voir l'annonce</Link>
          </div>
        ))}
    </div>
  );
};
