import React, { useEffect, useState } from "react";
import userApi from "../../../services/userApi";
import { initializeApp } from "firebase/app";
import {
  getStorage,
  ref,
  uploadBytesResumable,
  getDownloadURL,
} from "firebase/storage";
import "firebase/storage";

export const AddCurriculum = () => {
  const [succesMessage, setSuccesMessage] = useState();
  const [errors, setErrors] = useState({});
  const [currentUser, setCurrentUser] = useState({});
  const [file, setFile] = useState("test du path");
  const onFileChange = (e) => {
    setFile(e.target.files[0]);
  };
  var firebaseConfig = {
    apiKey: process.env.FIREBASE_API_KEY,
    authDomain: process.env.FIREBASE_AUTH_DOMAIN,
    projectId: process.env.FIREBASE_PROJECT_ID,
    storageBucket: process.env.FIREBASE_STORAGE_BUCKET,
    messagingSenderId: process.env.FIREBASE_MESSAGING_SENDER_ID,
    appId: process.env.FIREBASE_APP_ID,
  };
  const app = initializeApp(firebaseConfig);
  const storage = getStorage(app);

  const sendCurriculum = async (e) => {
    setSuccesMessage();
    e.preventDefault();
    const userId = currentUser.id;

    if (!file.name.endsWith(".pdf")) {
      setErrors({ file: "Le fichier doit être au format PDF" });
      return;
    }
    const storageRef = ref(storage, `${userId}/${file.name}`);
    const uploadTask = uploadBytesResumable(storageRef, file);

    uploadTask.on(
      "state_changed",
      (snapshot) => {
        let progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        if (progress < 100) {
          setSuccesMessage("Chargement du fichier en cours");
        } else {
          setSuccesMessage("Chargement du fichier terminé");
        }
      },
      (error) => {
        console.log(error);
      },
      () => {
        getDownloadURL(uploadTask.snapshot.ref).then((downloadURL) => {
          console.log("File available at", downloadURL);
          let data = {
            cvPath: downloadURL,
          };

          try {
            userApi.uploadCurriculum(data);
            console.log(currentUser.id);
          } catch (error) {
            console.log(error);
          }
        });
      }
    );
  };
  useEffect(() => {
    const fetchData = async () => {
      const data = await userApi.getUser();
      setCurrentUser(data);
    };
    fetchData();
  }, []);
  return (
    <div className="dashboardContent">
      <h2>Ajouter un CV</h2>
      <form>
        <input type="file" onChange={onFileChange} />

        {succesMessage && <p className="succesMessage">{succesMessage}</p>}

        {errors.file && <p className="errorMessage">{errors.file}</p>}
        <button type="submit" onClick={sendCurriculum}>
          Envoyer
        </button>
      </form>
    </div>
  );
};
