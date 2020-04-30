import { SET_VISITOR_STATE_START, SET_VISITOR_STATE_COMPLETE, CONNECTION_CHANGE_SUCCESS, CONNECTION_CHANGE_FAILURE, UPDATE_VISITOR_CARRIER_SUCCESS } from '../actions/types';

const initialState = {
	uid: null,
	device: null,
	connection: null,
	carrier: null,
	error: false,
	completed: false
};

export default (state = initialState, action) => {
	switch (action.type) {
		case SET_VISITOR_STATE_START:
			return {
				...state,
				...action.payload
			}
		case SET_VISITOR_STATE_COMPLETE:
			return {
				...state,
				uid: action.payload.uid,
				connection: action.payload.connection,
				carrier: action.payload.carrier,
				completed: true
			}
		case CONNECTION_CHANGE_SUCCESS:
			return {
				...state,
				connection: (action.payload.connection == 1) ? true : false,
				carrier: action.payload.carrier,
				error: false
			}
		case CONNECTION_CHANGE_FAILURE:
			return {
				...state,
				error: true
			}
		case UPDATE_VISITOR_CARRIER_SUCCESS:
			return {
				...state,
				carrier: action.payload.carrier,
				error: false
			}
		default:
			return state;
	}
}