import React, { useState } from "react";
import { FieldGroup } from "../../components/FieldGroup";
import Button from "../../components/Button";
import userApi from "../../services/userApi";

const UpdatePassword = () => {
  const [newPassword, setNewPassword] = useState();
  const [confirmNewPassword, setConfirmNewPassword] = useState();
  const [errors, setErrors] = useState({});
  const [succesMessage, setSuccesMessage] = useState();
  let data = {};

  const handleSubmit = async (e) => {
    setSuccesMessage();
    e.preventDefault();
    if (newPassword === confirmNewPassword) {
      data = {
        newPassword: newPassword,
      };
    } else {
      return setErrors({
        confirmNewPassword: "Les mots de passe ne correspondent pas",
      });
    }
    try {
      userApi.updatePassword(data).then((response) => {
        setErrors({});
        setSuccesMessage("Mot de passe modifié avec succès.");
      });
    } catch (error) {
      if (error.response && error.response.data) {
        const violations = error.response.data.violations;

        if (violations) {
          const apiErrors = {};
          violations.forEach(({ propertyPath, title }) => {
            apiErrors[propertyPath] = title;
          });
          setErrors(apiErrors);
        }
      }
    }
  };
  return (
    <div className="personalInfosContainer dashboardContent">
      <h2>Modifier mon mot de passe</h2>
      <form>
        <FieldGroup
          id="newPassword"
          label="Nouveau mot de passe"
          type="password"
          value={newPassword}
          onChange={(e) => setNewPassword(e.target.value)}
        />
        <FieldGroup
          id="confirmNewPassword"
          label="Confirmer le nouveau mot de passe"
          type="password"
          value={confirmNewPassword}
          onChange={(e) => setConfirmNewPassword(e.target.value)}
        />
        {succesMessage && <p className="succesMessage">{succesMessage}</p>}
        {errors.confirmNewPassword && (
          <p className="errorMessage">{errors.confirmNewPassword}</p>
        )}
        <Button
          type="submit"
          className="ctaButton"
          onClick={handleSubmit}
          title="Modifier"
        />
      </form>
    </div>
  );
};

export default UpdatePassword;
