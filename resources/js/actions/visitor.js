import {
	SET_VISITOR_STATE,
	CONNECTION_CHANGE_START,
	CONNECTION_CHANGE_SUCCESS,
	CONNECTION_CHANGE_FAILURE,
	RECEIVED_CARRIER_LIST,
	UPDATE_VISITOR_CARRIER_START,
	UPDATE_VISITOR_CARRIER_SUCCESS,
	UPDATE_VISITOR_CARRIER_FAIL
} from './types';

import axios from 'axios';

export const setVisitorData = (uid, device, connection, carrier) => async dispatch => {
	dispatch({
		type: SET_VISITOR_STATE,
		payload: {
			uid: uid,
			device: device,
			connection: connection,
			carrier: carrier
		}
	});
}

export const connectionChanged = (uid) => async dispatch => {
	dispatch({
		type: CONNECTION_CHANGE_START
	});

	try {
		const response = await axios.post('/api/connectionchanged', {
			uid: uid
		});

		if ( (typeof response.data.carriers_by_country == 'object') && (!response.data.visitor.carrier) ) {
			dispatch({
				type: RECEIVED_CARRIER_LIST,
				payload: response.data.carriers_by_country
			});
			// payload = {
			// 	connection: response.data.visitor.connection,
			// 	carrier: false
			// }
		} else {
			const payload = response.data;

			dispatch({
				type: CONNECTION_CHANGE_SUCCESS,
				payload: payload
			});
		}

	} catch (error) {
		dispatch({
			type: CONNECTION_CHANGE_FAILURE,
			payload: {
				error: true
			}
		});
	}
}