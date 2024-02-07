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
          <h1>Annonces d'emploi</h1>
          {advertissements.map((ad) => (
            <div key={ad.id} className="advertissementItem">
              <div className="advertissementItemClickable">
                <h2>{ad.title}</h2>
                <h3>{ad.city}</h3>
              </div>
              <Button path={"/annonce/" + ad.id} title="Voir l'annonce" />
            </div>
          ))}
        </div>
      </main>
    </div>
  );
};
