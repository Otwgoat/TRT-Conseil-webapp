import React, { useState } from "react";
import { CreateConsultant } from "./admin/CreateConsultant";

export const AdminDashboard = () => {
  const [displayContainer, setDisplayContainer] = useState("createConsultant");
  const displayForm = () => {
    setDisplayContainer("createConsultant");
  };
  return (
    <main className="dashboardPage" id="adminPage">
      <div className="dashboardNav">
        <h1>Admin Dashboard</h1>
        <div className="dashboardNavItem">
          <h2>Consultants</h2>
          <p onClick={displayForm}>Créer un consultant</p>
        </div>
      </div>
      <div className="dashboardContainer">
        {displayContainer === "createConsultant" && <CreateConsultant />}
      </div>
    </main>
  );
};
