import React, { useState, useContext, useEffect } from "react";
import { NavLink } from "react-router-dom";
import Button from "./Button";
import AuthContext from "../context/AuthContext";
import authAPI from "../services/authApi";
import { useNavigate } from "react-router-dom";

export const Header = () => {
  const navigate = useNavigate();
  const { isAuthenticated: auth, setIsAuthenticated } = useContext(AuthContext);
  const [isAuthenticated, setIsAuth] = useState(auth);
  const [startRedirect, setStartRedirect] = useState(false);
  const handleLogout = () => {
    authAPI.logout();
    setIsAuthenticated(false);
    setStartRedirect(true);
  };
  useEffect(() => {
    if (startRedirect === true) {
      navigate("/login");
    }
  }, [startRedirect]);

  return (
    <header>
      <div id="firstTier" className="navItem">
        <h1>TRT Conseil</h1>
      </div>
      <div id="secondTier" className="navItem">
        <NavLink to="/">Accueil</NavLink>
        <NavLink to="/annonces">Annonces</NavLink>
        <NavLink to="/contact">Contact</NavLink>
      </div>
      <div id="thirdTier" className="navItem">
        {!isAuthenticated ? (
          <>
            <Button id="loginButton" path="/login" title="Se connecter" />
            <Button
              id="registerButton"
              path="/inscription"
              title="S'inscrire"
            />
          </>
        ) : (
          <>
            <Button id="loginButton" path="/mon-espace" title="Mon espace" />
            <Button
              id="registerButton"
              path="/login"
              title="Se déconnecter"
              onClick={handleLogout}
            />
          </>
        )}
      </div>
    </header>
  );
};
