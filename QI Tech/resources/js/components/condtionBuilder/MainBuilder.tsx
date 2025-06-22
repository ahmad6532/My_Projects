import React, { useEffect, useState } from "react";
import Accordion from "@mui/material/Accordion";
import AccordionActions from "@mui/material/AccordionActions";
import AccordionSummary from "@mui/material/AccordionSummary";
import AccordionDetails from "@mui/material/AccordionDetails";
import ExpandMoreIcon from "@mui/icons-material/ExpandMore";
import ArrowDropDownIcon from "@mui/icons-material/ArrowDropDown";
import Button from "@mui/material/Button";
import {
    ButtonGroup,
    ClickAwayListener,
    Dialog,
    DialogActions,
    DialogContent,
    DialogContentText,
    DialogTitle,
    Grow,
    IconButton,
    MenuItem,
    MenuList,
    Paper,
    Popper,
    Stack,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableHead,
    TableRow,
    Typography,
} from "@mui/material";
import Conditions, { Condition } from "./Conditions";
import useDesigner from "../hooks/useDesigner";
import EditIcon from "@mui/icons-material/Edit";
import DeleteIcon from "@mui/icons-material/Delete";
import { ConditionType, FormElementInstance } from "../FormElements";

const MainBuilder = () => {
    const { selectedItem, updateElement, setSelectedItem, selectedPageId } =
        useDesigner();
    const [open, setOpen] = React.useState(false);
    const anchorRef = React.useRef<HTMLDivElement>(null);
    const [selectedIndex, setSelectedIndex ] = React.useState(1);
    const [openDialog, setOpenDialog] = useState(false);
    const [selectedCondtionId, setSelectedCondtionId] = useState<string | null>(null);

    const handleClick = () => {
        setOpenDialog(true);
    };

    const handleCloseDialog = () => {
        setOpenDialog(false);
        setSelectedCondtionId(null);
    };
    useEffect(() => {}, [selectedItem]);

    const handleEditCondition = (id: string) => {
        setSelectedCondtionId(id);
        setOpenDialog(true);
        console.log(getConditionsForEditing())
    };
    const getConditionsForEditing = (): ConditionType[] => {
        if (selectedCondtionId === null || !selectedItem) return [];
        
        const condition = (selectedItem.conditions || []).find(cond => cond.id === selectedCondtionId);
        
        const connectedConditions = (selectedItem.conditions || []).filter(cond => cond.connectedWith === selectedCondtionId);
        
        return [condition, ...connectedConditions].filter((c): c is ConditionType => c !== undefined);
    };
    const handleDeleteCondition = (index: number) => {
        if (!selectedItem) return;

        const conditions = selectedItem.conditions || [];
        const conditionToRemove = conditions[index];
        const idToRemove = conditionToRemove.id;
        const updatedConditions = conditions
        .filter((condition, i) => i !== index) 
        .filter(condition => condition.connectedWith !== idToRemove);
        const updatedQuestion = {
            ...selectedItem,
            conditions: updatedConditions,
        };
        updateElement(selectedPageId, selectedItem.id, updatedQuestion);
        setSelectedItem(updatedQuestion);
    };
    return (
        <div>
            <Accordion>
                <AccordionSummary expandIcon={<ExpandMoreIcon />}>
                    Conditions
                </AccordionSummary>
                <AccordionDetails sx={{px:'0px',pb:'0px'}}>
                    <Stack>
                        <ButtonGroup
                            variant="contained"
                            ref={anchorRef}
                            aria-label="Button group with a nested menu"
                            sx={{ alignSelf: "flex-end", m: 2 }}
                            color="primary"
                        >
                            <Button
                                onClick={handleClick}
                                sx={{ color: "white" }}
                            >
                                Add Logic
                            </Button>
                        </ButtonGroup>

                        {selectedItem && (selectedItem.conditions?.length || 0) > 0 && (
                            <TableContainer  sx={{ mt: 2 }}>
                                <Table>
                                    <TableHead>
                                        <TableRow className="header-cell">
                                            <TableCell
                                                sx={{
                                                    paddingY: 0,
                                                    color: "#909090",
                                                }}
                                            >
                                                Condition
                                            </TableCell>
                                            <TableCell
                                                sx={{
                                                    paddingY: 0,
                                                    color: "#909090",
                                                }}
                                            >
                                                Action
                                            </TableCell>
                                            <TableCell
                                                sx={{
                                                    paddingY: 0,
                                                    color: "#909090",
                                                }}
                                            >
                                                
                                            </TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                        {(selectedItem.conditions || []).filter(cond => !cond.connectedWith).map(
                                            (condition, index) => (
                                                <TableRow key={condition.id}>
                                                    <TableCell>
                                                        if {condition.operator} to {condition.value}
                                                    </TableCell>
                                                    <TableCell>
                                                        {condition.type}
                                                    </TableCell>
                                                    <TableCell>
                                                        <Stack direction='row' alignItems='center'>
                                                            <IconButton
                                                                size="small"
                                                                color="primary"
                                                                onClick={() =>
                                                                    handleEditCondition(
                                                                        condition.id
                                                                    )
                                                                }
                                                            >
                                                                <EditIcon sx={{fontSize:'18px'}} />
                                                            </IconButton>
                                                            <IconButton
                                                                size="small"
                                                                color="error"
                                                                onClick={() =>
                                                                    handleDeleteCondition(
                                                                        index
                                                                    )
                                                                }
                                                            >
                                                                <DeleteIcon sx={{fontSize:'18px'}} />
                                                            </IconButton>

                                                        </Stack>
                                                    </TableCell>
                                                </TableRow>
                                            )
                                        )}
                                    </TableBody>
                                </Table>
                            </TableContainer>
                        )}

                        <Dialog
                            fullWidth
                            sx={{
                                "& .MuiPaper-root": {
                                    background:'#F9F9F9',
                                },
                            }}
                            maxWidth="md"
                            open={openDialog}
                            onClose={handleCloseDialog}
                            aria-labelledby="alert-dialog-title"
                            aria-describedby="alert-dialog-description"
                        >
                            <DialogTitle
                                id="alert-dialog-title"
                                fontSize="28px"
                            >
                                Add New Logic Here
                            </DialogTitle>
                            <DialogContent >
                                {selectedCondtionId === null ? (<Conditions handleCloseDialog={handleCloseDialog}/>) : (<Conditions editableConditions={getConditionsForEditing()} handleCloseDialog={handleCloseDialog} />) }
                                
                            </DialogContent>
                        </Dialog>
                    </Stack>
                </AccordionDetails>
            </Accordion>
        </div>
    );
};

export default MainBuilder;
