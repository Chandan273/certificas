const getDefaultState = () => {
    return {
        tenancyDetail: {
            setting_info1: "",
            is_draft: "no",
            email_download: "",
            country: "England",
            tenancy_type: "",
            deposit_scheme: "",
            noticemonths: "TWO",
            landlord_display_name: "",
            is_landlord_content: "yes",
            landlord_email: "",
            landlord_address: "",
            landlord_phone: "",
            landlord_fax: "",
            d_o_agreement: null,
            tenancy_address: "",
            occcupationDate: "",
            fixed_term_form: "",
            fixed_term_last: "",
            breakclause: "no",
            earliestmonth: 6,
            rent_type: "Normal in advance (£x rent per x period)",
            current_rent: "",
            first_full_payment: "",
            payable_every_day: "",
            current_period: "Calendar month",
            monthly_rent_amount: null,
            number_of_months_in_advance: "6",
            rent_in_advance: "",
            date_advance_payment_due: "",
            first_payment_date: "",
            first_amount_payable: "",
            second_payment_date: "",
            second_amount_payable: "",
            third_payment_date: "",
            fourth_payment_date: "",
            on_payable_every_day: "",
            monthly_rent_amount_old: null,
            rent_box: "",
            paymentmethod: "",
            deposit_amount: "",
            deposit_behalf_tenants: "no",
            relevant_person_name: "",
            relevant_person_address: "",
            relevant_person_email: "",
            relevant_person_phone: "",
            relevant_person_fax: "",
            third_amount_payable: "",
            fourth_amount_payable: "",
            tenancyId: "",
            domestic_and_communal_area: "",
        },
        tenantDetail: {
            acceptService: "Yes",
            tenantEmail: "",
            tenants: [
                {
                    tenantDetailModel: null,
                    currentAddress: "",
                    tenantPostAddress: "",
                    tenancyPhone: "",
                    tenancyFax: "",
                    tenancyEmail: "",
                    fullName: "",
                },
            ],
        },
        gurantorDetail: {
            gurantor: [
                {
                    guarantorName: "",
                    guarantorAddress: "",
                    guarantorEmail: "",
                    tenantName: "",
                    fromApplicantsModel: "",
                },
            ],
            guarantorModel: "no",
            isMultipleTenant: "",
        },
        documents: {
            agreement_id: "",
            epcModel: "Yes",
            addEpcModel: "Upload",
            certificteModel: null,
            epc: null,
            gas: null,
            electricity: null,
            epcUrl: null,
            gasUrl: null,
            electricityUrl: null,
            otherDocument: [],
            addPermissionModel: "No",
            electricalSafetyModel: "Yes",
            gasSafetyModel: "Yes",
            certificatePdf: "",
            isPDFAttached: false,
            certificateError: false,
            certificateLength: false,
        },
        utilities: {
            agreement_id: "",
            telephone_internet:
                "Tenant pays directly for telephone and internet",
            tv_licence: "Tenant pays TV licence",
            water: "Tenant pays directly for all water",
            electricity: "Tenant pays electricity direct to supplier",
            gas_oil: "Tenant pays all gas during tenancy",
            council_tax: "Tenant pays Council Tax",
        },
        otherMatters: {
            otherTermsModel: "",
            permittedOccupiers: "None",
            noOfPeople: 1,
            signatories: [{ name: "", email: "" }],
            agentDetailsModel: "",
            agentLicence: "",
            saveModel: null,
            logoModel: null,
            logoUrl: null,
        },
        isLoader: true,
        allApplicants: [],
        tenancyId: null,
    };
};
export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        UPDATE_TENANCY(state, payload) {
            state.tenancyDetail = payload;
        },
        UPDATE_TENANT(state, payload) {
            let guarantorModel = "";
            // if (
            //     !state.gurantorDetail.guarantorModel.length ||
            //     state.gurantorDetail.guarantorModel[0].guarantorName == ""
            // ) {
            //     if (payload.tenants.length == 1) {
            //         guarantorModel = "no";
            //     } else if(payload.tenants.length > 1) {
            //         guarantorModel = "yes";
            //     }
            // }
            if(state.gurantorDetail.guarantorModel == 'no'){
                guarantorModel = "no";
            }else{
                guarantorModel = "yes";
            }
            state.gurantorDetail.guarantorModel = guarantorModel;
            state.tenantDetail = payload;
        },
        UPDATE_GURANTOR(state, payload) {
            state.gurantorDetail = payload;
        },
        UPDATE_DOCUMENTS(state, payload) {
            state.documents = payload;
        },
        UPDATE_UTILITIES(state, payload) {
            state.utilities = payload;
        },
        UPDATE_OTHERS(state, payload) {
            state.otherMatters = payload;
        },
        RESET_DATA(state) {
            Object.assign(state, getDefaultState());
        },
        UPDATE_APPLICANTS(state, payload) {
            state.allApplicants = payload;
        },
        UPDATE_TENANCY_ID(state, payload) {
            state.tenancyId = payload;
        },
        UPDATE_EMAIL_ID(state, payload) {
            state.tenancyDetail.email_download = payload;
        },
        UPDATE_MULTIPLE_TENANT(state, payload) {
            state.gurantorDetail.isMultipleTenant = payload;
        },
        UPDATE_GURANTOR_FIELDS(state, payload) {
            state.gurantorDetail.gurantor = payload;
        },
        UPDATE_CERTIFICATE_NUMBER(state, payload) {
            state.documents.certificteModel = payload;
        },
    },
    actions: {
        addTotenancyDetail(context, payload) {
            context.commit("UPDATE_TENANCY", payload);
        },
        addTotenantDetail(context, payload) {
            context.commit("UPDATE_TENANT", payload);
        },
        addToGurantor(context, payload) {
            context.commit("UPDATE_GURANTOR", payload);
        },
        addToDocuments(context, payload) {
            context.commit("UPDATE_DOCUMENTS", payload);
        },
        addToUtilities(context, payload) {
            context.commit("UPDATE_UTILITIES", payload);
        },
        addToOthers(context, payload) {
            context.commit("UPDATE_OTHERS", payload);
        },
        resetData(context, payload) {
            context.commit("RESET_DATA", payload);
        },
        setApplicants(context, payload) {
            context.commit("UPDATE_APPLICANTS", payload);
        },
        setTenancyId(context, payload) {
            context.commit("UPDATE_TENANCY_ID", payload);
        },
        setEmailId(context, payload) {
            context.commit("UPDATE_EMAIL_ID", payload);
        },
        setMultipleTenant(context, payload) {
            context.commit("UPDATE_MULTIPLE_TENANT", payload);
        },
        setGurantorFields(context, payload) {
            context.commit("UPDATE_GURANTOR_FIELDS", payload);
        },
        setCertificateNumber(context, payload) {
            context.commit("UPDATE_CERTIFICATE_NUMBER", payload);
        },
    },
    getters: {
        getTenancy: function (state) {
            const tenanyData = state.tenancyDetail;
            return tenanyData;
        },
        getTenant: function (state) {
            const tenantData = state.tenantDetail;
            return tenantData;
        },
        getGurantor: function (state) {
            const GurantorData = state.gurantorDetail;
            return GurantorData;
        },
        getDocuments: function (state) {
            const documentsData = state.documents;
            return documentsData;
        },
        getUtilities: function (state) {
            const utilitiesData = state.utilities;
            return utilitiesData;
        },
        getOthers: function (state) {
            const othersData = state.otherMatters;
            return othersData;
        },
        getApplicants: function (state) {
            const applicantsData = state.allApplicants;
            return applicantsData;
        },
        getTenancyId: function (state) {
            return state.tenancyId;
        },
        getMultipleTenant: function (state) {
            return state.gurantorDetail.isMultipleTenant;
        },
        getCertificateNumber: function (state) {
            return state.documents.certificteModel;
        },
    },
};
