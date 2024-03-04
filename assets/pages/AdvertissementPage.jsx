import React, { useEffect, useState } from "react";
import advertissementApi from "../services/advertissementApi";
import { Header } from "../components/Header";
import Button from "../components/Button";

export const AdvertissementPage = () => {
  const [advertissements, setAdvertissements] = useState([]);
  useEffect(() => {
    const fetchData = async () => {
      const data = await advertissementApi.findAllAdvertissements();
      console.log(data);
      setAdvertissements(data);
    };
    fetchData();
  }, []);

  return (
    <div className="container">
      <Header />
      <main id="advertissementPage">
        <div className="advertissementsContainer">
          <h2>Annonces d'emploi</h2>
          {advertissements.map((ad) => (
            <div key={ad.id} className="advertissementItem">
              <div className="advertissementItemContent">
                <h3 className="adTitle">{ad.title}</h3>
                <p className="boldText">{ad.city}</p>
                <p>{ad.description}</p>
              </div>
              <Button path={"/annonce/" + ad.id} title="Voir l'annonce" />
            </div>
          ))}
        </div>
      </main>
    </div>
  );
};
