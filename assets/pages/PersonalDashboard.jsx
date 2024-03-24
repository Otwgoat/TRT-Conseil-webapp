import React, { useContext, useEffect, useState } from "react";
import { Header } from "../components/Header";
import authAPI from "../services/authApi";
import { CandidateDashboard } from "./dashboard/CandidateDashboard";
import { RecruiterDashboard } from "./dashboard/RecruiterDashboard";
import AuthContext from "../context/AuthContext";
import { ConsultantDashboard } from "./dashboard/ConsultantDashboard";
import { useNavigate } from "react-router-dom";
import { AdminDashboard } from "./dashboard/AdminDashboard";

export const PersonalDashboard = () => {
  const navigate = useNavigate();
  const [isAuthenticated, setIsAuthenticated] = useState(
    useContext(AuthContext)
  );
  const userRole = authAPI.getUserType();
  useEffect(() => {
    if (!isAuthenticated) {
      navigate("/login");
    }
  }, [isAuthenticated]);

  return (
    <div className="container">
      <Header />
      {userRole === "candidate" ? (
        <CandidateDashboard />
      ) : userRole === "recruiter" ? (
        <RecruiterDashboard />
      ) : userRole === "consultant" ? (
        <ConsultantDashboard />
      ) : userRole === "admin" ? (
        <AdminDashboard />
      ) : (
        <>
          <p>Aucun utilisateur trouvé</p>
        </>
      )}
    </div>
  );
};
