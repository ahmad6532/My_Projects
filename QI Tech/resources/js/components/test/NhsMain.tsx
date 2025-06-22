import * as React from "react";
import Box from "@mui/material/Box";
import Stepper from "@mui/material/Stepper";
import Step from "@mui/material/Step";
import StepButton from "@mui/material/StepButton";
import Button from "@mui/material/Button";
import Typography from "@mui/material/Typography";
import { Stack } from "@mui/material";

export default function NhsMain() {
    const [activeStep, setActiveStep] = React.useState(0);
    const [completed, setCompleted] = React.useState<{ [k: number]: boolean }>({});

    const totalSteps = () => {
        return steps.length;
    };

    const isStepRequired = (step: number) => {
        // Define which steps are required
        return step === 1; // Example: Step 2 is required
    };

    const isStepCompleted = (step: number) => {
        // Implement your completion logic here
        return completed[step];
    };

    const handleNext = () => {
        const isCurrentStepRequired = isStepRequired(activeStep);
        
        if (isCurrentStepRequired && !isStepCompleted(activeStep)) {
            alert("Please complete the required action for this step.");
            return;
        }

        const newActiveStep = activeStep + 1;
        setActiveStep(newActiveStep);
    };

    const handleBack = () => {
        setActiveStep((prevActiveStep) => prevActiveStep - 1);
    };

    const handleStep = (step: number) => () => {
        setActiveStep(step);
    };

    const handleComplete = () => {
        const newCompleted = completed;
        newCompleted[activeStep] = true;
        setCompleted(newCompleted);
        handleNext();
    };

    const handleReset = () => {
        setActiveStep(0);
        setCompleted({});
    };

    const steps = [
        "Select campaign settings",
        "Create an ad group",
        "Create an ad",
    ];

    return (
        <div style={{ background: "#F3F3F3" }}>
            <header className="p-3 px-5" style={{ background: "white", boxShadow: "0px 2px 0px #19b394" }}>
                <div>
                    <Typography variant="h3" fontWeight="bold" fontFamily="Littera Text" color="#19b394">
                        Record Patient Safety Events
                    </Typography>
                    <Typography variant="h6" color="#909090" fontFamily="Littera Text">
                        Form Submission Page
                    </Typography>
                </div>
            </header>
            <Box sx={{ width: "60%", mx: "auto", mt: 5, minHeight: "70vh", padding: "20px" }}>
                <Stepper nonLinear activeStep={activeStep}>
                    {steps.map((label, index) => (
                        <Step key={label}>
                            <StepButton onClick={handleStep(index)} color="inherit">
                                {label}
                            </StepButton>
                        </Step>
                    ))}
                </Stepper>
                <div className="main-question-wrapper prev" style={{ minHeight:'60vh',marginTop:'1rem' }}>
                    {activeStep === 0 && (
                        <Stack>
                            <Typography variant="h6">
                                Which things were involved in the incident?
                            </Typography>
                            <Typography variant="body2">
                                These options act as a trigger for subsequent more detailed questions required by our national patient safety data partners (e.g. MHRA, estate services etc).
                            </Typography>
                        </Stack>
                    )}
                    {activeStep === 1 && (
                        <Typography>
                            Content for Step 2: Create an ad group
                        </Typography>
                    )}
                    {activeStep === 2 && (
                        <Typography>
                            Content for Step 3: Create an ad
                        </Typography>
                    )}
                </div>
                <Box mt={2}>
                    <Button disabled={activeStep === 0} onClick={handleBack}>
                        Back
                    </Button>
                    <Button variant="contained" color="primary" onClick={handleNext}>
                        {activeStep === steps.length - 1 ? "Finish" : "Next"}
                    </Button>
                </Box>
            </Box>
        </div>
    );
}
