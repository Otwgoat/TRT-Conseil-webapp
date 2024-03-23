import React, { useEffect } from "react";
import { Header } from "../components/Header";
import { useForm, ValidationError } from "@formspree/react";
import { FieldGroup } from "../components/FieldGroup";

const Contact = () => {
  const formspreeEndpoint = () => {
    if (process.env.NODE_ENV === "development") {
      return process.env.FORMSPREE_ENDPOINT;
    } else {
      return REACT_APP_FORMSPREE_ENDPOINT;
    }
  };
  const [state, handleSubmit] = useForm(formspreeEndpoint());

  useEffect(() => {
    console.log(process.env.NODE_ENV);
  }, []);
  return (
    <div className="container">
      <Header />
      <main id="contactPage">
        <form onSubmit={handleSubmit} method="POST">
          <FieldGroup
            id="firstname"
            label="Prénom"
            type="text"
            placeholder="Votre prénom"
          />
          <FieldGroup
            id="lastname"
            label="Nom"
            type="text"
            placeholder="Votre nom"
          />
          <FieldGroup
            id="email"
            label="Email"
            type="email"
            placeholder="Votre adresse email"
          />
          <ValidationError prefix="Email" field="email" errors={state.errors} />
          <div className="fieldGroup">
            <label htmlFor="message">Message</label>
            <textarea id="message" name="message" placeholder="Votre message" />
            <ValidationError
              prefix="Message"
              field="message"
              errors={state.errors}
            />
          </div>

          <ValidationError
            prefix="Message"
            field="message"
            errors={state.errors}
          />
          {state.succeeded && (
            <p className="succesMessage">Votre message a bien été envoyé.</p>
          )}
          <button
            className="ctaButton"
            type="submit"
            disabled={state.submitting}
          >
            Envoyer
          </button>
        </form>
      </main>
    </div>
  );
};

export default Contact;
