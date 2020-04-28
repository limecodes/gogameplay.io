import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class Searching extends Component {

	constructor(props) {
		super(props);

		this.state = {
			searching: false
		}
	}

	componentDidMount() {
		var self = this;

		setTimeout(function() {
			self.setState({
				searching: true
			});
		}, 1000);
	}

	render() {
		const Progress = () => {
			if (!this.state.searching) {
				return (
					<div className="progress">
						<div className="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style={{ width: '100%'}}></div>
					</div>
				);
			} else {
				return (<div></div>);
			}
		};

		return (
			<div className="card" style={{ marginTop: '1rem' }}>
				<div className="card-body">
					{ (this.state.searching) ? <div><span className="badge badge-success">check</span>Coupon Found</div> : <div>Searching</div> }
					<Progress />
				</div>
			</div>
		);
	}

}

export default Searching;