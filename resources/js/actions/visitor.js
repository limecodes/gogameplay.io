import { SET_VISITOR_STATE, CONNECTION_CHANGE_START, CONNECTION_CHANGE_SUCCESS, CONNECTION_CHANGE_FAILURE } from './types';

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

		const payload = response.data;

		dispatch({
			type: CONNECTION_CHANGE_SUCCESS,
			payload: payload
		});
	} catch (error) {
		dispatch({
			type: CONNECTION_CHANGE_FAILURE
		});
	}
}