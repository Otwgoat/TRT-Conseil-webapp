import React from "react";
import { Header } from "../components/Header";

export const Homepage = () => {
  return (
    <div className="container">
      <Header />
      <main id="homepageContainer">
        <div className="homepageContent">
          <h2>Qui sommes-nous ?</h2>
          <p>
            TRT Conseil est une agence de recrutement spécialisée dans
            l’hôtellerie et la restauration. Fondée en 2014, la société s’est
            agrandie au fil des ans et possède dorénavant plus de 12 centres
            dispersés aux quatre coins de la France
          </p>
        </div>
        <div className="homepageContent">
          <h2>Notre mission</h2>
          <p>
            Notre mission est de trouver les meilleurs talents pour nos clients,
            et de les aider à trouver le poste qui leur convient le mieux. Nous
            sommes convaincus que le bon recrutement est un élément clé pour la
            réussite d’une entreprise.
          </p>
        </div>
        <div className="homepageContent">
          <h2>Nos valeurs</h2>
          <p>
            La confiance, l’intégrité et la transparence sont les valeurs qui
            nous animent au quotidien. Nous nous engageons à respecter ces
            valeurs dans toutes nos relations avec nos clients et nos candidats.
          </p>
        </div>
      </main>
    </div>
  );
};
export default Homepage;
